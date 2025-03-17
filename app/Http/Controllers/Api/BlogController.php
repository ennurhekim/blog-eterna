<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    // Blog listesi
    public function index(Request $request)
    {
        $query = Blog::query();

        // Arama parametresi varsa başlık veya içeriğe göre filtrele
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('content', 'LIKE', "%{$search}%");
        }

        $blogs = $query->latest()->paginate($request->per_page ?? 10);
        return response()->json($blogs);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'published_at' => 'nullable|date',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id'
            ]);

            $blog = new Blog();
            $blog->title = $request->title;
            $blog->content = $request->content;
            $blog->published_at = $request->published_at;
            $blog->user_id = auth()->id();

            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('blog_images', 'public');
                $blog->cover_image = $path;
            }

            $blog->save();

            if ($request->has('category_ids')) {
                $blog->categories()->attach($request->category_ids);
            }

            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }

    // Belirli bir blog yazısını göster
    public function show($idOrSlug)
    {
        try {
            $blog = Blog::with('user')
                ->where(function ($query) use ($idOrSlug) {
                    $query->where('id', $idOrSlug)
                        ->orWhere('slug', $idOrSlug);
                })
                ->first();

            if (!$blog) {
                return response_json(false, __("validation.some_error"), ['message' => 'Blog bulunamadı'], 404);
            }
            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }

    // Blog güncelleme
    public function update(Request $request, $id)
    {

        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'published_at' => 'nullable|date',
            ]);

            $blog = Blog::find($id);
            if (!$blog) {
                return response_json(false, __("validation.some_error"), ["message" => "Blog Bulunamadı"]);
            }
            $blog->title = $request->title;
            $blog->content = $request->content;
            $blog->published_at = $request->published_at;

            // Yeni kapak görseli yükleme
            if ($request->hasFile('cover_image')) {
                if ($blog->cover_image) {
                    Storage::disk('public')->delete($blog->cover_image);
                }
                $path = $request->file('cover_image')->store('blog_images', 'public');
                $blog->cover_image = $path;
            }

            $blog->save();
            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }

    // Blog silme
    public function destroy($id)
    {
        try {
            $blog = Blog::find($id);
            if (!$blog) {
                return response_json(false, __("validation.some_error"), ["message" => "Blog Bulunamadı"]);
            }
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $blog->delete();
            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
}
