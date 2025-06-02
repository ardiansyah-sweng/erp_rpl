<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

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

    public static function countWarehouse()
    {
        return self::count();
    }

}
