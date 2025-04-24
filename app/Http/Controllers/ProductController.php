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

    public function create()
    {
        return view('product.add'); 
    }

    public function store(Request $request)
    {
        Product::addProduct($request->all()); 

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }
}
