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
        
        $product = Product::find($id);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($productColumns as $key => $column) {
                // Lewati field id
                if ($column != 'id' && isset($input[$column])) {
                    $product->$column = $input[$column];
                }
            }
            
            $product->updated_at = now();
            
            $product->save();
            
            DB::commit();
            
            return redirect()->route('product.list')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal memperbarui produk', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
        

}

