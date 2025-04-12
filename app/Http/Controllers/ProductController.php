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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|unique:products,product_id',
            'product_name' => 'required',
            'product_type' => 'required',
            'product_category' => 'required',
            'product_description' => 'nullable',
        ]);

        Product::create($validated);

        return redirect()->route('product.list')->with('success', 'Produk berhasil ditambahkan!');
    }
}
