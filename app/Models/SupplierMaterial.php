<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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

    public function getSupplierMaterialByCategory($kategori, $supplier)
    {
        return DB::table('supplier_product as sp')
            ->join('products as p', 'sp.product_id', '=', 'p.product_id')
            ->join('category as c', 'p.product_category', '=', 'c.id') // ganti category -> categories
            ->join('item as i', 'p.product_id', '=', 'i.product_id')
            ->where('c.id', $kategori) // pakai kolom id
            ->where('sp.supplier_id', $supplier)
            ->select(
                'i.id as item_id',
                'i.sku',
                'i.item_name',
                'i.measurement_unit',
                'i.avg_base_price',
                'i.selling_price',
                'i.purchase_unit',
                'i.sell_unit',
                'i.stock_unit',
                'p.product_id',
                'p.product_name',
                'p.product_type',
                'c.category as category_name',
                'sp.company_name',
                'sp.base_price'
            )
            ->get();
    }
}
