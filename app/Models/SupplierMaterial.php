<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierMaterial extends Model
{
    // Menentukan nama tabel yang digunakan oleh model ini
    protected $table = 'bill_of_material';

    // Menentukan kolom-kolom yang bisa diisi
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

    // Menambahkan method untuk mengambil data dari tabel supplier_product
    public static function getSupplierMaterial()
    {
        return DB::table('supplier_product')->get();
    }
}
