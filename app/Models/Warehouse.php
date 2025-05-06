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



    public static function addWarehouse($data)
    {
        return self::create($data);
    }

    public static function getWarehouseByID($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getAllWarehouse($search = null)
    {
        $query = self::query();

        if ($search) {
            $query->where('warehouse_name', 'LIKE', "%{$search}%")
                ->orWhere('warehouse_address', 'LIKE', "%{$search}%")
                ->orWhere('warehouse_telephone', 'LIKE', "%{$search}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

    public static function countBranchByStatus()
    {
        return [
            'aktif' => self::where('is_active', 1)->count(),
            'nonaktif' => self::where('is_active', 0)->count(),
        ];
    }

    public static function countWarehouse()
    {
        return self::count();
    }
    public static function getRandomWarehouseID()
    {
        return self::inRandomOrder()->first()->id;
    }
    public static function links()
    {
        return self::paginate(10)->links();
    }
}
