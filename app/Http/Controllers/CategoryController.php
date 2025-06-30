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
            'parent_id' => 'nullable|integer|exists:category,id',
            'active' => 'required|boolean'
        ]);

        $updatedCategory = Category::updateCategory($id, $request->only(['category', 'parent_id', 'active']));

        if (!$updatedCategory) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return redirect()->route('category.edit', $id)->with('success', 'Kategori berhasil diupdate');
    }

    public function updateCategoryById($id)
    {
        $category = Category::getCategoryById($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return view('category.edit', compact('category'));
    }

    public function getCategoryById($id)
    {
        $category = (new Category())->getCategoryById($id);
        return response()->json($category);
    }

    //Search Category fitur
    public function searchCategory(Request $request)
    {
        $keyword = $request->input('q');

        $category = Category::when($keyword, function ($query) use ($keyword) {
            $query->where('category', 'like', '%' . $keyword . '%');
        })->get();

        return view('category.list', compact('category'));
    }

    // delete category
    public function deleteCategory($id)
    {
        $deleted = Category::deleteCategoryById($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan atau gagal dihapus.');
        }
    }
}