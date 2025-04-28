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

    public function addproduct
    (Request $request)
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

{
    $products = [
        1 => [
            'id' => 1,
            'product_id' => 'KAOS',
            'product_name' => 'Kaos T-Shirt',
            'product_type' => 'Finished',
            'category' => ['category' => 'Pakaian'],
            'product_description' => 'Kaos T-Shirt',
            'created_at' => '2025-03-12 19:48:13',
            'updated_at' => '2025-03-12 19:48:13'
        ],
        2 => [
            'id' => 2,
            'product_id' => 'TOPI',
            'product_name' => 'Topi',
            'product_type' => 'Finished',
            'category' => ['category' => 'Aksesoris'],
            'product_description' => 'Topi',
            'created_at' => '2025-03-12 19:48:13',
            'updated_at' => '2025-03-12 19:48:13'
        ],
        3 => [
            'id' => 3,
            'product_id' => 'TASS',
            'product_name' => 'Tas',
            'product_type' => 'Finished',
            'category' => ['category' => 'Aksesoris'],
            'product_description' => 'Tas',
            'created_at' => '2025-03-12 19:48:13',
            'updated_at' => '2025-03-12 19:48:13'
        ]
    ];

    if (!isset($products[$id])) {
        abort(404, 'Product not found.');
    }

    $productData = $products[$id];
    $productData['category'] = (object)$productData['category'];
    $product = (object)$productData;

    return view('product.detail', compact('product'));
}

}

