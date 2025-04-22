<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    public static function getSupplierMaterial()
    {
        //get() saya ubah menjadi paginate() agar membagi halaman seperti program list.blade lainnya.
        return DB::table('supplier_product')->paginate(10);
    }
}