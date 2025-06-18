<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaterialModel extends Model
{
    protected $table = 'supplier_materials'; // sesuaikan dengan nama tabel
    public static function countSupplierMaterialByCategory($kategori, $supplier)
    {
        return self::where('category', $kategori)
                    ->where('supplier_id', $supplier)
                    ->count();
    }
}
