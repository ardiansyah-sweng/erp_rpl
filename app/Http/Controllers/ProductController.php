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
        // Validasi input, pastikan product_id unik kecuali untuk produk ini sendiri
        $validated = $request->validate([
            'product_id' => 'required|string|unique:products,product_id,' . $id . ',product_id',
            'product_name' => 'required|string|min:3|max:255',
            'product_type' => 'required|string',
            'product_category' => 'required|string',
            'product_description' => 'nullable|string|max:255',
        ]);
    
        // Ambil produk berdasarkan product_id lama
        $product = Product::where('product_id', $id)->first();
    
        // Jika produk tidak ditemukan
        if (!$product) {
            return abort(404, 'Produk tidak ditemukan');
        }
    
        // Update data produk
        $product->product_id = $validated['product_id'];
        $product->product_name = $validated['product_name'];
        $product->product_type = $validated['product_type'];
        $product->product_category = $validated['product_category'];
        $product->product_description = $validated['product_description'] ?? null;
        $product->updated_at = now();
    
        // Simpan perubahan
        $product->save();
    
        // Redirect dengan pesan sukses
        return redirect()->route('product.list')->with('success', 'Produk berhasil diperbarui!');
    }

}
