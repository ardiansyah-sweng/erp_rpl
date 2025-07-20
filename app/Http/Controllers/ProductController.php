<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
    }

    public function generatePDF()
    {
    $products = Product::getAllProducts(); // Gunakan method buatan Wahyu Dwiyanto

    $pdf = Pdf::loadView('product.pdf', compact('products'));
    return $pdf->stream('daftar-produk.pdf');
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


}
