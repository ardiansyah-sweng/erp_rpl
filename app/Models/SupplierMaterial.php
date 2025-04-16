<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaterial extends Model
{
    public function getSupplierMaterial()
    {
        return DB::table('supplier_product')->get();
    }
}
