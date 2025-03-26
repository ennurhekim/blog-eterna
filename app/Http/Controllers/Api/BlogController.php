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

    public function index(Request $request)
    {
        $query = Blog::with(["categories", 'user']); // Category ile birlikte çekiyoruz.

        // Admin değilse sadece 'published' olanları göster
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            $query->where("status", 'published');
        }

        // Arama parametresi varsa başlık veya içerikte ara
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("status", 'published')
                    ->where(function ($subQuery) use ($search) {
                        $subQuery->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('content', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Sayfalama işlemi
        $blogs = $query->latest()->paginate($request->per_page ?? 10);

        return response()->json($blogs);
    }
    public function area1(Request $request)
    {
        $query = Blog::with(["categories", 'user']); // Kategoriler ve kullanıcı ile birlikte çekiyoruz.
        $blogs = $query->latest()->take(1)->get();
        $blogs->map(function ($blog) {
            $blog->image_url = $blog->getFirstMediaUrl('cover_images') ?? null;
            return $blog;
        });
        return response()->json($blogs);
    }
    public function area2(Request $request)
    {
        $query = Blog::with(["categories", 'user']);
        $blogs = $query->latest()->skip(1)->take(3)->get();
        $blogs->map(function ($blog) {
            $blog->image_url = $blog->getFirstMediaUrl('cover_images') ?? null;
            return $blog;
        });
        return response()->json($blogs);
    }
    public function area3(Request $request)
    {
        $query = Blog::with(["categories", 'user']); // Kategoriler ve kullanıcı ile birlikte çekiyoruz.
        $blogs = $query->latest()->skip(4)->take(2)->get();
        $blogs->map(function ($blog) {
            $blog->image_url = $blog->getFirstMediaUrl('cover_images') ?? null;
            return $blog;
        });
        return response()->json($blogs);
    }
    public function area4(Request $request)
    {
        $query = Blog::with(["categories", 'user']); // Kategoriler ve kullanıcı ile birlikte çekiyoruz.
        $blogs = $query->latest()->skip(6)->take(2)->get();
        $blogs->map(function ($blog) {
            $blog->image_url = $blog->getFirstMediaUrl('cover_images') ?? null;
            return $blog;
        });
        return response()->json($blogs);
    }
    public function area5(Request $request)
    {
        $query = Blog::with(["categories", 'user']); // Kategoriler ve kullanıcı ile birlikte çekiyoruz.
        $blogs = $query->latest()->skip(1)->take(5)->get();
        $blogs->map(function ($blog) {
            $blog->image_url = $blog->getFirstMediaUrl('cover_images') ?? null;
            return $blog;
        });
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
                'cover_images' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
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
                'cover_images' => $blog->getFirstMediaUrl('cover_images'), // Medya dosyasının URL'sini döndürelim.
            ]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
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
            $blogImage = $blog->getFirstMediaUrl('cover_images') ?? null;
            return response_json(true, __("validation.success_process"), [
                "data" => $blog,
                'image' => $blogImage,
            ]);
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
                'cover_images' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
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
            if ($request->hasFile('cover_images')) {
                if ($blog->hasMedia('cover_images')) {
                    $blog->getMedia('cover_images')->each(function ($media) {
                        $media->delete();
                    });
                }
                $blog->addMedia($request->file('cover_image'))
                    ->toMediaCollection('cover_images');
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
            $blog->clearMediaCollection('cover_images');

            $blog->delete();
            return response_json(true, __("validation.success_process"), ['blog' => $blog]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
}
