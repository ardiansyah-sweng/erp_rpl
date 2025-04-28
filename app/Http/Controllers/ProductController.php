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

    public function getProductById($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('product.detail', compact('product'));
    }

    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|string|unique:products,product_id',
            'product_name' => 'required|string',
            'product_type' => 'required|string',
            'product_category' => 'required|string',
            'product_description' => 'nullable|string',
        ]);

        Product::create($validatedData);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function create()
    {
        return view('product.add');
    }
}