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

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|string|min:3',
            'product_name' => 'required|string|min:3',
            'product_type' => 'required|string',
            'product_category' => 'required|exists:categories,id',
            'product_description' => 'required|string|min:3',
        ]);
    
        $data = [
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'product_type' => $request->product_type,
            'product_category' => $request->product_category,
            'product_description' => $request->product_description,
            'created_at' => now(), 
            'updated_at' => now(),
        ];
    
        $productModel = new ProductModel();
        $productModel->updateProduct($id, $data);
    
        return redirect()->route('product.list')->with('success', 'Produk berhasil diupdate!');
    }
}

