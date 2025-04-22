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
<<<<<<< HEAD
}
=======
}
>>>>>>> b02cf2841aec6d2466d3724255d19caa37e77a32
