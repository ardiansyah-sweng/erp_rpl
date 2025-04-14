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
        
        try {
            $product = Product::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $productColumns = config('db_constants.column.products', []);
        
        $rules = [];
        foreach ($productColumns as $key => $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                $rules[$column] = 'required|min:3';
            }
        }
        
        if (isset($productColumns['product_category'])) {
            $categoryTable = config('db_constants.table.category', 'categories');
            $rules[$productColumns['product_category']] = 'required|exists:' . $categoryTable . ',id';
        }
        
        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($productColumns as $key => $column) {
                if ($column != 'id' && isset($input[$column])) {
                    $product->$column = $input[$column];
                }
            }
            
            $product->updated_at = now();
            
            $product->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal memperbarui produk via API', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
