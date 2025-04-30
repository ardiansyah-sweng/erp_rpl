<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    protected $table = 'bill_of_material';
    protected $fillable = [
        'bom_id',
        'bom_name',
        'measurement_unit',
        'sku',
        'total_cost',
        'active',
        'created_at',
        'updated_at',
    ];

    public static function storeMaterial($data)
    {
        return self::create([
            'bom_id' => $data->bom_id,
            'bom_name' => $data->bom_name,
            'measurement_unit' => $data->measurement_unit,
            'sku' => $data->sku,
            'total_cost' => $data->total_cost,
            'active' => $data->active,
        ]);
    }

    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->get();
    }

}



