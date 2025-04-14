<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategoryById($id)
    {
        $category = (new Category())->getCategoryById($id);

        if (!$category) {
            return abort(404, 'Kategori tidak ditemukan');
        }

        return view('category.detail', compact('category'));
    }

    public function getCategoryAll(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::getAllCategory($search);
        return view('category.list', ['categories' => $categories]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|min:3|unique:category,category',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);

        $category = new Category();
        $category->addCategory([
            'category' => $request->category,
            'parent_id' => $request->parent_id ?? 0,
            'active' => $request->active,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('category.list')->with('success', 'Kategori berhasil ditambahkan!');
    }
}