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
        }catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['error' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            if (!auth()->user()->hasAnyRole(['admin'])) {
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
        }catch (\Exception $e) {
            return response_json(false, __("validation.some_error"), ['error' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            if (!auth()->user()->hasAnyRole(['admin'])) {
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
        // Alt kategorileri al
        $subCategories = $category->children;

        // Eğer alt kategori varsa, her birini sil
        foreach ($subCategories as $subCategory) {
            $this->deleteCategoryWithChildren($subCategory);
        }

        // Kategoriyi soft delete yap
        $category->delete();
    }
}
