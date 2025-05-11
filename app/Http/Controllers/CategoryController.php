<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{

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
        ]);

        return redirect()->route('category.list')->with('success', 'Kategori berhasil ditambahkan!');
    }
    public function getCategoryList() 
    {
        $category = Category::getAllCategory();
        return view('category.list', compact('category'));
    }
    public function printCategoryPDF()
    {
        $categories = Category::getCategory(); // kita tambahkan method ini di bawah
        $pdf = Pdf::loadView('product.category.pdf', compact('categories'));
        return $pdf->stream('laporan_kategori.pdf'); 
    }

    public function updateCategory(Request $request, $id) 
    {
        $validated = $request->validate([
            'category' => 'required|string|min:3',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ]);

        $updatedCategory = Category::updateCategory($id, $request->only(['category', 'parent_id']));

        if (!$updatedCategory) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $updatedCategory
        ]);

        // return view('category.detail', compact('category')); 
        // apabila halaman detail kategori sudah ada harap untuk di uncomment return view
        // dan return response nya di hapus
    }

} //CategoryController