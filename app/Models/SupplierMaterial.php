<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->get();
    }
    public static function countSupplierMaterial(){
        //menghitung jumlah item bertipe RM yang dipasok oleh seluruh supplier
        return DB::table('supplier_product')
            ->join('products', 'supplier_product.product_id', '=', 'products.product_id')
            ->where('products.product_type', 'RM')
            ->count();
    }

}