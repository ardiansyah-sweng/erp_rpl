<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.whouse');
        $this->fillable = array_values(config('db_constants.column.whouse') ?? []);
    }

    public function getWarehouseById($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getWarehouseAll()
    {
        // Menggunakan Eloquent untuk mengambil semua data dari tabel warehouse
        return self::all();
    }

}
