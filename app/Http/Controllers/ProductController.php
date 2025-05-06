<?php

namespace App\Http\Controllers;
use App\Helpers\EncryptionHelper;
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
    
    public function detail($id)
    {
        $productId = EncryptionHelper::decrypt($id);
        $product = Product::where('product_id', $productId)->firstOrFail();
        return view('product.detail', compact('product'));
    }
}
