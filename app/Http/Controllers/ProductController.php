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
        $Product = (new Product())->getProductById($id);

        if (!$Product) {
            return abort(404, 'Product tidak ditemukan');
        }

        return response()->json($Product);
       # return view('Product.detail', compact('Product'));
    }
}
