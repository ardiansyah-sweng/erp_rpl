<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
    }

    public function addProduct(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|size:4|unique:products,product_id|alpha_num',
            'product_name' => 'required|string|max:35',
            'product_type' => 'required|in:Finished,Raw Material',
            'product_category' => 'required|integer',
            'product_description' => 'required|string|max:255',
        ]);
    }

}
