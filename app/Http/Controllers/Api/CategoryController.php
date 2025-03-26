<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        try {
            $categories = Category::with('children')->whereNull('parent_id')->get();
            return response_json(true, __("validation.success_process"), ['categories' => $categories]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['error' => $e->getMessage()]);
        }
    }
    public function get($idOrSlug = "")
    {
        try {
            // ID veya slug'a göre kategori arama
            $category = Category::with('children')
                ->where(function ($query) use ($idOrSlug) {
                    $query->where('id', $idOrSlug)
                        ->orWhere('slug', $idOrSlug);
                })
                ->first();
            if (!$category) {
                return response_json(false, __("validation.category_not_found"), []);
            }
            return response_json(true, __("validation.success_process"), ['category' => $category]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['error' => $e->getMessage()]);
        }
    }
    public function getCategoryBlogs($slug)
    {
        try {
            $category = Category::where('slug', $slug)
                ->with(['blogs' => function ($query) {
                    $query->with(['user'])->latest(); // User ilişkisini de çekiyoruz
                }])
                ->firstOrFail();

            // Her blog için resim URL'sini ekleyelim
            $blogs = $category->blogs->map(function ($blog) {
                $blog->image_url = $blog->getFirstMediaUrl('cover_images') ?? null; // Resim URL'sini ekliyoruz
                return $blog;
            });
            
            if ($blogs->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Bu kategoride blog yazısı bulunmamaktadır.']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kategoriye ait bloglar başarıyla alındı.',
                'data' => [
                    'category' => $category,
                    'blogs' => $blogs
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            if (!auth()->user()->can('create category')) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ]);

            $category = Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            return response_json(true, __("validation.success_process"), ['category' => $category]);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['error' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            if (!auth()->user()->can('delete category')) {
                return response_json(false, __("validation.error_auth"), "");
            }
            $category = Category::findOrFail($id);

            // Alt kategorileri de soft delete yap
            $this->deleteCategoryWithChildren($category);

            return response_json(true, __("validation.success_process"), ['message' => 'Kategori ve alt kategorileri başarıyla silindi.']);
        } catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['error' => $e->getMessage()]);
        }
    }

    /**
     * **Kategori ve alt kategorileri silen recursive fonksiyon**
     */
    private function deleteCategoryWithChildren($category)
    {
        if (!auth()->user()->can('delete category')) {
            return response_json(false, __("validation.error_auth"), "");
        }
        $subCategories = $category->children;
        foreach ($subCategories as $subCategory) {
            $this->deleteCategoryWithChildren($subCategory);
        }
        $category->delete();
    }
}
