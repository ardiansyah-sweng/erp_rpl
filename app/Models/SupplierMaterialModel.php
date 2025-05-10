<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaterialModel extends Model
{
    protected $table = 'supplier'; // nama tabel pada database
    protected $primaryKey = 'supplier_id'; // nama kolom utama / id pada tabel supplier
    protected $timestamps = false;

    public static function getSupplierMaterialByID($id)
    {
        return \DB::table('supplier')
            ->join('supplier_product', 'supplier.supplier_id', '=', 'supplier_product.supplier_id')
            ->join('products', 'supplier_product.product_id', '=', 'products.id')
            ->where('supplier.supplier_id', $id)
            ->select(
                'supplier.supplier_id',
                'supplier.company_name',
                'supplier.address',
                'supplier.phone_number',
                'supplier.bank_account',
                'products.id as product_id',
                'products.name as product_name'
            )
            ->get();
    }

}
