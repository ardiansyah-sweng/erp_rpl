<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterialModel extends Model
{
    protected $table = 'supplier_product';

    public static function getSupplierMaterialByProductType($supplierId, $productType)
    {
        return DB::table('supplier_product as sp')
            ->join('products as p', 'sp.product_id', '=', 'p.product_id')
            ->where('sp.supplier_id', $supplierId)
            ->where('p.product_type', $productType)
            ->select('sp.*', 'p.product_type', 'p.product_description')
            ->get();
    }
}
