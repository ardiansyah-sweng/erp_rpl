<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategoryById($id)
    {
        $category = (new Category())->getCategoryById($id);
        return response()->json($category);
    }
}
