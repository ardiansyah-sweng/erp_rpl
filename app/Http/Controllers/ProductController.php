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
public function updateProduct($id)
    {
        $input = request()->all();
        
        $productColumns = config('db_constants.column.products', []);
        
        $rules = [];
        foreach ($productColumns as $key => $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                $rules[$column] = 'required|min:3';
            }
        }
        
        if (isset($productColumns['category'])) {
            $categoryTable = config('db_constants.table.category', 'categories');
            $rules[$productColumns['category']] = 'required|exists:' . $categoryTable . ',id';
        }
        
        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $result = Product::updateProduct($id, $input);
        
        if (!$result['success']) {
            return redirect()->back()
                ->with('error', $result['message'])
                ->withInput();
        }
        
        return redirect()->route('product.list')->with('success', $result['message']);
    }

}
