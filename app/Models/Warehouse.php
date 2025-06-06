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

        // Tetapkan nama tabel dan kolom dari konfigurasi
        $this->table = config('db_constants.table.whouse');
        $this->fillable = array_values(config('db_constants.column.whouse') ?? []);
    }

    /**
     * Ambil data warehouse berdasarkan ID
     */
    public function getWarehouseById($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * Update data warehouse berdasarkan ID
     */
    public function updateWarehouse($id, $data)
    {
        $warehouse = $this->getWarehouseById($id);

        if (!$warehouse) {
            return false;
        }

        return $warehouse->update($data);
    }
}
