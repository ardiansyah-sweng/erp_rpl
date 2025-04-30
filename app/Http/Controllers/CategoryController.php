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
    public function printCategoryPDF()
    {
    $categories = Category::getCategory(); // kita tambahkan method ini di bawah
    $pdf = Pdf::loadView('product.category.pdf', compact('categories'));
    return $pdf->stream('laporan_kategori.pdf'); 
    }
} //CategoryController