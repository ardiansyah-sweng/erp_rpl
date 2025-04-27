<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierMaterial extends Model
{
    protected $table = 'supplier_product';
    protected $primaryKey = ['supplier_id', 'product_id'];
    public $incrementing = false;

    protected $rules = [
        'company_name' => 'required|string|max:100',
        'product_name' => 'required|string|max:50',
        'base_price' => 'required|integer|min:0',
    ];

    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->get();
    }

    public function updateSupplierMaterial($supplier_id, $product_id, array $data)
    {
        try {
            // Validate data
            $validator = Validator::make($data, $this->rules);
            if ($validator->fails()) {
                \Log::error('Validation failed: ' . json_encode($validator->errors()));
                return false;
            }

            $result = DB::table('supplier_product')
                ->where('supplier_id', $supplier_id)
                ->where('product_id', $product_id)
                ->update([
                    'company_name' => $data['company_name'],
                    'product_name' => $data['product_name'],
                    'base_price' => $data['base_price'],
                    'updated_at' => now()
                ]);

            return $result > 0;
        } catch (\Exception $e) {
            \Log::error('Error updating supplier material: ' . $e->getMessage());
            return false;
        }
    }
}