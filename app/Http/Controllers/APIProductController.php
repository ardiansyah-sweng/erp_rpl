<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\LogAvgBasePrice;

use Illuminate\Http\Request;

class APIProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getAvgBasePrice()
    {
        $avgBasePrices = LogAVgBasePrice::all();
        return response()->json($avgBasePrices);
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $result = Product::updateProduct($id, $input);
        
        if (!$result['success']) {
            $statusCode = strpos($result['message'], 'tidak ditemukan') !== false ? 404 : 500;
            return response()->json($result, $statusCode);
        }
        
        return response()->json($result);
    }
}
