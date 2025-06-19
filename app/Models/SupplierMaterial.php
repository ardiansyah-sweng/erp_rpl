<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
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

    public static function countSupplierMaterial(){
        return DB::table('supplier_product as sp')
            ->join('products as p', function($join) {
                $join->on(DB::raw('LEFT(sp.product_id, LOCATE("-", sp.product_id) - 1)'), '=', 'p.product_id');
            })
            ->where('p.product_type', '=', 'RM')
            ->distinct('p.product_id')
            ->count(DB::raw('DISTINCT p.product_id'));
    }

    public static function countSupplierMaterialByCategory($kategori, $supplier)
    {
        return DB::table('supplier_product');
        return self::where('category', $kategori)
                    ->where('supplier_id', $supplier)
                    ->count();
    }
}