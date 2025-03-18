<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Belirli bir blogun yorumlarını getir
    public function index($idOrSlug)
    {
        $blog = Blog::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();
        $comments = Comment::with('user')->where('blog_id', $blog->id)->latest()->get();
        return response()->json($comments);
    }

    public function store(Request $request, $idOrSlug)
    {
        try {
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
            if (!auth()->user()->hasAnyRole(['admin'])) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $comment = Comment::where('id', $commentId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $comment->delete();
            return response()->json(['message' => 'Yorum başarıyla silindi.']);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
}
