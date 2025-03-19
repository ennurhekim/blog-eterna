<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

            if (!auth()->user()->can('create post')) {
                return response_json(false, __("validation.error_auth"), "");
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'published_at' => 'nullable|date',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
                'status' => 'required|in:draft,published',
            ]);

            $blog = new Blog();
            $blog->title = $request->title;
            $blog->content = $request->content;
            $blog->published_at = $request->published_at;
            $blog->user_id = auth()->id();
            $blog->status = $request->status ?? 'draft'; // Varsayılan olarak taslak
            $blog->save(); // Önce kaydediyoruz ki medya ekleyebilelim.

            if ($request->hasFile('cover_image')) {
                $blog->addMedia($request->file('cover_image'))->toMediaCollection('cover_images');
            }
            // Kategori ekleme
            if ($request->has('category_ids')) {
                $blog->categories()->attach($request->category_ids);
            }

            return response_json(true, __("validation.success_process"), [
                'blog' => $blog,
                'cover_image' => $blog->getFirstMediaUrl('cover_images'), // Medya dosyasının URL'sini döndürelim.
            ]);
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
    public function update(Request $request, $idOrSlug)
    {
        try {
            if (!auth()->user()->can('edit post')) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $blog = Blog::where('id', $idOrSlug)
                ->orWhere('slug', $idOrSlug)
                ->first();

            if (!$blog) {
                return response_json(false, __("validation.some_error"), ["message" => "Blog Bulunamadı"]);
            }
            if (!auth()->user()->hasRole('admin') && $blog->user_id !== auth()->id()) {
                return response_json(false, __("validation.error_auth"), ["message" => "Sadece kendi yazınızı güncelleyebilirsiniz."]);
            }
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'published_at' => 'nullable|date',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
            ]);
            $blog->title = $request->title;
            $blog->content = $request->content;
            $blog->published_at = $request->published_at;
            if ($request->status) {
                $blog->status = $request->status;
            }
            if ($request->hasFile('cover_image')) {
                if ($blog->hasMedia('cover_image')) {
                    $blog->getMedia('cover_image')->each(function ($media) {
                        $media->delete();
                    });
                }
                $blog->addMedia($request->file('cover_image'))
                    ->toMediaCollection('cover_image');
            }
            if ($request->has('category_ids')) {
                $blog->categories()->sync($request->category_ids);
            }
            $blog->save();

            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
    public function destroy($idOrSlug)
    {
       
        try {
            $user = auth()->user();
            $blog = Blog::where('id', $idOrSlug)
                ->orWhere('slug', $idOrSlug)
                ->first();

            if (!$blog) {
                return response_json(false, __("validation.some_error"), ["message" => "Blog Bulunamadı"]);
            }
            // Admin tüm postları silebilir, yazar ise sadece kendi postunu silebilir
            if ($user->role !== 'admin' && $blog->user_id !== $user->id) {
                return response_json(false, __("validation.error_auth"), "");
            }
            // Spatie Media Library kullanarak resmi sil
            $blog->clearMediaCollection('cover_image');

            $blog->delete();
            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
}
