<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaterialModel extends Model
{
    // TODO: Implementasi method getMaterial (Muhamad Fadhli Akbar)
    public static function getMaterial()
    {
        return DB::table('item')
            ->select('sku', 'item_name', 'measurement_unit', 'stock')
            ->where('type', 'RM')
            ->get();
    }
}
