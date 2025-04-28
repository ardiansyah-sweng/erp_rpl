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

    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'category' => 'required|string|min:3',
            'parent_id' => 'integer|exists:categories,id',
        ]);

        $category->update([
            'category' => $validated['category'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $category
        ]);

        // return view('category.detail', compact('category')); 
        // apabila halaman detail kategori sudah ada harap untuk di uncomment return view
        // dan return response nya di hapus
    }
    
} //CategoryController