<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // mengambil nama tabel dan kolom dari file konfigurasi
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    public static function deleteSupplierPICByID($id)
    {
        $pic = self::find($id);
        if ($pic) {
            $pic->delete();
            return true;
        }
        return false;
    }
}
