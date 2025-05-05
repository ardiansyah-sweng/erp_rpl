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

    public function getUpdateProduct($product_id)
    {
        $product = Product::getUpdateProduct($product_id)->first();

        if (!$product) {
            return response()->json(['message' => 'Data Produk Tidak Tersedia'], 404);
        }

        return response()->json([
            'message' => 'Data Produk berhasil ditemukan',
            'data' => $product,
        ]);
    }

    public function updateProduct(Request $request, $product_id)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer'
        ]);

        $updatedProduct = Product::updateProduct($product_id, $request->all());

        if (!$updatedProduct) {
            return response()->json(['message' => 'Data Produk Tidak Tersedia'], 404);
        }

        return response()->json([
            'message' => 'Data Produk berhasil diperbarui',
            'data' => $updatedProduct,
        ]);
    }
}