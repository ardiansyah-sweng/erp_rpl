<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            return response()->json([
                'success' => false,
                'message' => 'Warehouse tidak ditemukan.',
            ]);
        }

        // Cek apakah warehouse digunakan di tabel assortment_production
        $usedInAssortment = DB::table('assortment_production')
            ->where('rm_whouse_id', $id)
            ->orWhere('fg_whouse_id', $id)
            ->exists();

        if ($usedInAssortment) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse tidak dapat dihapus karena sedang digunakan di tabel assortment_production.',
            ], 400);
        }

        // Lakukan penghapusan
        $warehouse->delete();

        return response()->json([
            'success' => true,
            'message' => 'Warehouse berhasil dihapus.',
        ]);
    }

    public static function addWarehouse($data)
    {
        if (empty($data)) {
            throw new \Exception('Data tidak boleh kosong.');
        }

        if (is_object($data)) {
            $data = (array) $data;
        }

        return self::create([
            'warehouse_name' => $data['warehouse_name'],
            'warehouse_address' => $data['warehouse_address'],
            'warehouse_telephone' => $data['warehouse_telephone'],
            'is_rm_whouse' => $data['is_rm_whouse'],
            'is_fg_whouse' => $data['is_fg_whouse'],
            'is_active' => $data['is_active'],
        ]);
    }
    public static function getWarehouseAll()
    {
        return self::orderBy('created_at', 'asc')->paginate(10);
    }

}
