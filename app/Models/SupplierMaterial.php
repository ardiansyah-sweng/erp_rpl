<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    protected $table = 'supplier_product';
    public $incrementing = false;
    protected $primaryKey = ['supplier_id', 'product_id'];

    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->get();
    }

    public static function updateSupplierMaterial($supplierId, $productId, $data)
    {
        return DB::table('supplier_product')
            ->where('supplier_id', $supplierId)
            ->where('product_id', $productId)
            ->update($data);
    }
}