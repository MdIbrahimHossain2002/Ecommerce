<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(
            Category::with('children')->whereNull('parent_id')->orderBy('sort_order')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data['slug'] = Str::slug($data['name']);

        return response()->json(Category::create($data), 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
