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

