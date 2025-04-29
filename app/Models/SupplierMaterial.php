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

    public static function updateSupplierMaterial($id, array $data)
    {
        return DB::table('supplier_product')
            ->where('id', $id)
            ->update($data);
    }
}
