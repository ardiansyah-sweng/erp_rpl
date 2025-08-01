<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Helpers\EncryptionHelper;

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
    }

    public function getProductById($id)
    {
        $productId = EncryptionHelper::decrypt($id);
        $product = (new Product())->getProductById($productId);

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
        // Validasi data
        $request->validate([
            'product_id' => 'required',
            'product_name' => 'required',
            'product_type' => 'required',
            'category' => 'required',
            // dst
        ]);

        // Simpan ke database
        Product::create([
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'product_type' => $request->product_type,
            'category' => $request->category,
            'product_description' => $request->product_description,
            // dst
        ]);

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('product.list')->with('success', 'Produk berhasil ditambahkan!');
    }
    public function updateProduct(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'product_name' => 'required|string|max:35',
            'product_type' =>  'required|string|max:12',
            'product_category' => 'required|integer',
            'product_description' => 'nullable|string|max:255',
        ]);

        $Updateproduct = Product::updateProduct($id, $request->only(['product_name','product_type','product_category','product_description']));

        return $Updateproduct;
    }


    public function searchProduct($keyword)
    {
        $products = Product::getProductByKeyword($keyword);
        return view('product.list', compact('products'));
    }

    public function showAddProductForm()
    {
        $categories = Category::all();
        return view('product.add', compact('categories'));
    }
}
