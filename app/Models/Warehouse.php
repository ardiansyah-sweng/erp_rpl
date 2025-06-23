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
    public static function countWarehouse()
    {
        return self::count();
    }
    
     public function updateWarehouse($id, $data)
    {
        $warehouse = $this->getWarehouseById($id);

        if (!$warehouse) {
            return false;
        }

        return $warehouse->update($data);
    }
    public function searchWarehouse($keyword)
    {
        return self::where(function ($query) use ($keyword) {
            $query->where('warehouse_name', 'like', "%{$keyword}%")
                ->orWhere('warehouse_address', 'like', "%{$keyword}%")
                ->orWhere('warehouse_telephone', 'like', "%{$keyword}%");
        })->get();
    }
    public function deleteWarehouse($id)
    {
        $warehouse = $this->getWarehouseById($id);

        if (!$warehouse) {
            return false;
        }

        return $warehouse->delete();
    }
     public static function getWarehouseAll()
    {
        return self::orderBy('created_at', 'asc')->paginate(10);
    }
}
