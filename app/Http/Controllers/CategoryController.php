<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
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

