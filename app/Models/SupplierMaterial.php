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

    public static function supplierMaterialSearch($keyword)
    {
         return self::where('nama_material', 'LIKE', "%$keyword%")->get();
    }

}
