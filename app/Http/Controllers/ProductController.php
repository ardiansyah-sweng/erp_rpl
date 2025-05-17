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


    public function deleteProduct($id)
    {
        // Panggil fungsi deleteProductById dari model Product
        $deleted = Product::deleteProductById($id);
        // Redirect kembali ke halaman list dengan pesan sukses atau gagal
        if ($deleted) {
            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau gagal dihapus.');
        }
    }

}
