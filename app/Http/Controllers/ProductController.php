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
    
        $product = new Product();
        $product->product_id = $validated['product_id'];
        $product->product_name = $validated['product_name'];
        $product->product_type = $validated['product_type'];
        $product->product_category = $validated['product_category'];
        $product->product_description = $validated['product_description'];
        $product->save();

    return redirect()->back()->with('success', 'Product berhasil ditambahkan!');
    }

}
