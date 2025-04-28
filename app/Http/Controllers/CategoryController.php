<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::with(['parent', 'children'])->find($id);

        if (!$category) {
            abort(404, 'Category not found');
        }

        return view('product.category.show', compact('category'));
    }

    public function index()
    {
        $categories = Category::all();

        return view('product.category.index', compact('categories'));
    }
}
