<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class CommentController extends Controller
{
    // Belirli bir blogun yorumlarını getir
    public function index($idOrSlug)
    {
        $blog = Blog::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();
        $comments = Comment::with('user', 'blog')->where('blog_id', $blog->id)->latest()->get();
        return response()->json($comments);
    }

    public function store(Request $request, $idOrSlug)
    {
        try {
            if (!auth()->user()->can('create comment')) {
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
            ]);

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
