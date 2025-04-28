<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function addCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|min:3|unique:category,category',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);
            //colum category
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
} //CategoryController