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
    

    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            \Log::error('Product not found with ID: ' . $id);
            return redirect()->back()->with('error', 'Product not found.');
        }
    
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|min:3',
                'product_description' => 'required|string|min:3',
                'product_type' => 'required|string',
                'category_id' => 'required|exists:categories,id' // ganti dengan nama field yang sesuai di DB
            ]);
    
            $validated['updated_at'] = now();
    
            $result = $product->update($validated);
    
            if ($result) {
                return redirect()->route('product.detail', ['id' => $id])->with('success', 'Product updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update product. No changes were made.');
            }
        } catch (\Exception $e) {
            \Log::error('Update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
}

        $product = (new Product())->getProductById($id);

        if (!$product) {
            return abort(404, 'Product tidak ditemukan');
        }
       return view('product.detail', compact('product'));
    }
}

