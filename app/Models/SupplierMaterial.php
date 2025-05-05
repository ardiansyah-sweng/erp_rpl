<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    protected $table = 'supplier_product';

    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->paginate(10);
    }
    
    public static function supplierMaterialSearch($keyword)
    {
         return self::where('supplier_id', 'LIKE', "%$keyword%")
         ->where('nama', 'LIKE', "%$keyword%")
         ->where('alamat', 'LIKE', "%$keyword%")
         ->get();
    }

}
