<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CommentMail;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CommentController extends Controller
{
    // Belirli bir blogun yorumlarını getir
    public function index($idOrSlug)
    {

        if (!auth()->check() || !auth()->user()->can('update comment')) {
            $blog = Blog::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();
            $comments = Comment::with('user', 'blog')->where(['blog_id' => $blog->id, 'status' => "approved"])->get();
            return response_json(true, __("validation.success_process"), ['comments' => $comments]);
        }
        $blog = Blog::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();
        $comments = Comment::with('user', 'blog')->where('blog_id', $blog->id)->latest()->get();
        return response_json(true, __("validation.success_process"), ['comments' => $comments]);
    }
    public function all()
    {
        if (!auth()->check() || !auth()->user()->can('update comment')) {
            $comments = Comment::with('user', 'blog')->get();
        } else {
            // Admin yetkisi varsa tüm yorumları getir
            $comments = Comment::with('user', 'blog')->get();
        }

        return response_json(true, __("validation.success_process"), ['comments' => $comments]);
    }

    public function store(Request $request, $idOrSlug)
    {

        try {

            if (!auth()->check() && auth()->user()->can('create comment')) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);
            $blog = Blog::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();

            $comment = Comment::create([
                'user_id' => auth()->id(),
                'blog_id' => $blog->id,
                'content' => $request->content,
            ]); // Manuel olarak 'name' ekleyin
            $comment = Comment::with("user")->find($comment->id);
            $commentData = ["name" => auth()->user()->name ?? "", "email" => auth()->user()->email ?? "", "message" => $request->content ?? ""];
            if ($comment) {
                Mail::to('ennur2828@gmail.com')->queue(new CommentMail($commentData));
            }
            return response_json(true, __("validation.success_process"), ['comment' => $comment]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }

    public function destroy($commentId)
    {
        try {
            if (!auth()->user()->can('delete comment')) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $comment = Comment::where('id', $commentId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $comment->delete();

            return response_json(true, __("validation.success_process"), ['comment' => $comment]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['message' => $e->getMessage()]);
        }
    }

    // Yorumları onayla (approve)
    public function approve($commentId)
    {
        try {

            if (!auth()->user()->can('approve comment')) {
                return response_json(false, __("validation.error_auth"), "");
            }

            $comment = Comment::findOrFail($commentId);
            $comment->status = 'approved';
            $comment->save();

            return response_json(true, __("validation.comment_approved"), ['comment' => $comment]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['message' => $e->getMessage()]);
        }
    }

    // Yorumları reddet (reject)
    public function reject($commentId)
    {
        try {

            if (!auth()->user()->can('reject comment')) {
                return response_json(false, __("validation.error_auth"), "");
            }

            $comment = Comment::findOrFail($commentId);
            $comment->status = 'rejected';
            $comment->save();

            return response_json(true, __("validation.comment_rejected"), ['comment' => $comment]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['message' => $e->getMessage()]);
        }
    } // Yorumları beklemeye alma (pending)
    public function pending($commentId)
    {
        try {
            if (!auth()->user()->can('pending comment')) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $comment = Comment::findOrFail($commentId);
            $comment->status = 'pending';
            $comment->save();

            return response_json(true, __("validation.comment_rejected"), ['comment' => $comment]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['message' => $e->getMessage()]);
        }
    }
    // Yorumları filtrele (onaylı, reddedilmiş, beklemede)
    public function filterComments($status)
    {
        try {
            $validStatuses = ['pending', 'approved', 'rejected'];
            if (!in_array($status, $validStatuses)) {
                return response_json(false, __("validation.invalid_status"), "");
            }
            $comments = Comment::with(['user', 'blog'])->where('status', $status)->latest()->get();
            return response_json(true, __("validation.success_process"), ['comments' => $comments]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['message' => $e->getMessage()]);
        }
    }
}
