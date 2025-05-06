<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; // Untuk validasi kategori
use Validator;

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
    }

    public function getProductById($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return abort(404, 'Product tidak ditemukan');
        }

        return view('product.detail', compact('product'));
    }

    // Fungsi untuk update produk
    public function updateProduct(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'product_name' => 'required|min:3|max:255', // Nama produk harus lebih dari 3 karakter
            'category_id' => 'required|exists:categories,id', // Kategori harus ada di database
            'product_description' => 'required|max:255', // Deskripsi produk
        ]);

        // Ambil data produk yang akan diupdate
        $product = Product::find($id);
        
        // Jika produk tidak ditemukan
        if (!$product) {
            return abort(404, 'Product tidak ditemukan');
        }

        // Memperbarui data produk
        $product->product_name = $validated['product_name'];
        $product->category_id = $validated['category_id'];
        $product->product_description = $validated['product_description'];
        $product->updated_at = now(); // Perbarui waktu updated_at
        
        // Simpan perubahan
        $product->save();

        // Redirect atau beri respons setelah berhasil
        return redirect()->route('product.list')->with('success', 'Product berhasil diperbarui!');
    }
}
