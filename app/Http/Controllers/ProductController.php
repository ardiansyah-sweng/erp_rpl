<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::all(); // Mengambil semua data produk
        return view('product.list', compact('products'));
    }
}
