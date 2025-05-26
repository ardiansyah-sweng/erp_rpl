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
        $product = (new Product())->getProductById($id);

        if (!$product) {
            return abort(404, 'Product tidak ditemukan');
        }
       return view('product.detail', compact('product'));
    }


    // $productData = $products[$id];
    // $productData['category'] = (object)$productData['category'];
    // $product = (object)$productData;

    // return view('product.detail', compact('product'));


    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|string|unique:products,product_id',
            'product_name' => 'required|string',
            'product_type' => 'required|string',
            'product_category' => 'required|string',
            'product_description' => 'nullable|string',
        ]);

        Product::addProduct($validatedData);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }
    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name'        => 'required|string|min:3|max:35',
            'product_type'        => 'required|string|min:3|max:12',
            'product_category'    => 'required|integer|exists:categories,id',
            'product_description' => 'nullable|string|max:255',
        ]);

        $updatedProduct = Product::updateProduct($id, $request->only(['product_name', 'product_type', 'product_category', 'product_description']));

        if (!$updatedProduct) {
            return response()->json([ 'message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data'    => $updatedProduct,
        ]);
    }
}
