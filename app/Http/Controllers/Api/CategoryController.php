<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return response()->json($categories);
    }


    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ]);

            $category = Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            return response_json(true, __("validation.success_process"), ['category' => $category]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
}
