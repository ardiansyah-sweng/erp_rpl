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
    ];
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

    public static function getSupplierMaterialById($id)
    {
        return DB::table('supplier_product')->where('id', $id)->first();
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

    public static function countSupplierMaterial()
    {
        return DB::table('supplier_product as sp')
            ->join('products as p', function ($join) {
                $join->on(DB::raw('LEFT(sp.product_id, LOCATE("-", sp.product_id) - 1)'), '=', 'p.product_id');
            })
            ->where('p.product_type', '=', 'RM')
            ->distinct('p.product_id')
            ->count(DB::raw('DISTINCT p.product_id'));
    }

   
    public static function addSupplierMaterial($data)
    {
        if (empty($data)) {
            throw new \Exception('Data tidak boleh kosong.');
        }

        if (is_object($data)) {
        $data = (array) $data;
    }

        return self::create([
            'supplier_id' => $data['supplier_id'],
            'company_name' => $data['company_name'],
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'base_price' => $data['base_price'],
        ]);
    }
    public static function searchSupplierMaterial($keyword)
    {
        return DB::table('supplier_product')
            ->where(function ($query) use ($keyword) {
                $query->where('supplier_id', 'like', '%' . $keyword . '%')
                    ->orWhere('company_name', 'like', '%' . $keyword . '%')
                    ->orWhere('product_id', 'like', '%' . $keyword . '%')
                    ->orWhere('product_name', 'like', '%' . $keyword . '%');
            })
            ->paginate(10);
    }

    public static function countSupplierMaterialFoundByKeyword($keyword)
    {
        return DB::table('supplier_product')
            ->where('supplier_id', 'like', '%' . $keyword . '%')
            ->orWhere('company_name', 'like', '%' . $keyword . '%')
            ->orWhere('product_id', 'like', '%' . $keyword . '%')
            ->orWhere('product_name', 'like', '%' . $keyword . '%')
            ->count();
    }

    public static function countSupplierMaterialByType($type, $supplierId)
    {
        return DB::table('supplier_product as sp')
            ->join('products as p', function ($join) {
                $join->on(DB::raw('LEFT(sp.product_id, LOCATE("-", sp.product_id) - 1)'), '=', 'p.product_id');
            })
            ->where('p.product_type', $type)
            ->where('sp.supplier_id', $supplierId)
            ->distinct('p.product_id')
            ->count(DB::raw('DISTINCT p.product_id'));
    }

    public static function countSupplierMaterialByID($supplierID)
    {
        return DB::table('supplier_product as sp')
            ->join('products as p', function ($join) {
                $join->on(DB::raw('LEFT(sp.product_id, LOCATE("-", sp.product_id) - 1)'), '=', 'p.product_id');
            })
            ->where('p.product_type', 'RM') // hanya RM
            ->where('sp.supplier_id', $supplierID)
            ->distinct('p.product_id')
            ->count(DB::raw('DISTINCT p.product_id'));
    }
    
    public function getSupplierMaterialByProductType($supplier_id, $product_type)
{
    if (!in_array($product_type, ['HFG', 'FG', 'RM'])) {
        return response()->json([], 400);
    }

    $data = DB::table('supplier_product')
        ->join('products', DB::raw("SUBSTRING_INDEX(supplier_product.product_id, '-', 1)"), '=', 'products.product_id')
        ->join('item', 'products.product_id', '=', 'item.product_id')
        ->where('supplier_product.supplier_id', $supplier_id)
        ->where('products.product_type', $product_type)
        ->select(
            'supplier_product.supplier_id',
            'supplier_product.company_name',
            'supplier_product.product_id',
            'products.product_type',
            'supplier_product.base_price',
            'item.item_name',
            'item.measurement_unit',
            'item.stock_unit'
        )
        ->get();

    return response()->json($data);
}

}
