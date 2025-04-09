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

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }
    public static function getPICByID($id)
{
    return self::where('supId', $id)->first(); // ambil 1 baris pertama berdasarkan supId
}

}
