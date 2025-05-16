<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    protected $table = 'supplier_product';
    protected $fillable = [
        'supplier_id',
        'company_name',
        'product_id',
        'product_name',
        'base_price',
        'created_at',
        'updated_at',
    ];

    public static function addSupplierMaterial($data)
    {
        return self::create([
            'supplier_id' => $data->supplier_id,
            'company_name' => $data->company_name,
            'product_id' => $data->product_id,
            'product_name'=> $data->product_name,
            'base_price' => $data->base_price,
        ]);
    }

    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->paginate(10);
    }

    public static function getSupplierMaterialByKeyword($keyword)
    {
        return DB::table('supplier_product')
            ->where('supplier_id', 'like', '%' . $keyword . '%')
            ->orWhere('company_name', 'like', '%' . $keyword . '%')
            ->orWhere('product_id', 'like', '%' . $keyword . '%')
            ->orWhere('product_name', 'like', '%' . $keyword . '%')
            ->get();
    }

}



    public static function updateSupplierMaterial($id, array $data)
    {
        try {
            return DB::table('supplier_product')
                ->where('id', $id)
                ->update($data);
        } catch (\Exception $e) {
            return false;
        }
    }
}
