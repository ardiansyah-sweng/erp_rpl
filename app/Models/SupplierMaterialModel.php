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
        return self::find($id);
    }

}
