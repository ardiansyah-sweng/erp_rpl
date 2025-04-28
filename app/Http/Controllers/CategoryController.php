<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function updateCategory(Request $request, $id) {
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

}
