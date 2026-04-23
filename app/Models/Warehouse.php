<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;

class Warehouse extends Model
{
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Constants\WarehouseColumns;

class Warehouse extends Model
{
    use HasFactory;

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
<<<<<<< HEAD
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
=======
        $this->table = config('db_tables.warehouse');
        $this->fillable = WarehouseColumns::getFillable();
    }

    public static function getWarehouseAll($search = null)
    {
        $query = self::query();
        //perubahan pemanggilan
         if ($search) {
            $query->where(WarehouseColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(WarehouseColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(WarehouseColumns::PHONE, 'LIKE', "%{$search}%");
        }

        return $query->orderBy(WarehouseColumns::CREATED_AT, 'asc')->paginate(config('pagination.branch_per_page'));
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    public static function addWarehouse($data)
    {
        if (empty($data)) {
            throw new \Exception('Data tidak boleh kosong.');
        }

        if (is_object($data)) {
            $data = (array) $data;
        }

<<<<<<< HEAD
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
        return self::paginate(10);
=======
        return self::create($data);
    }

    public static function getWarehouseById($id)
    {
        return self::find($id);
    }

    public static function countWarehouse()
    {
        return self::count();
    }

    public static function countActiveWarehouse()
    {
        return self::where(WarehouseColumns::IS_ACTIVE, 1)->count();
    }

    public static function countInactiveWarehouse()
    {
        return self::where(WarehouseColumns::IS_ACTIVE, 0)->count();
    }

    public function updateWarehouse($id, $data)
    {
        $warehouse = self::getWarehouseById($id);

        if (!$warehouse) {
            return false;
        }

        return $warehouse->update($data);
    }

public function searchWarehouse($keyword)
    {
        return self::where(function ($query) use ($keyword) {
            $query->where(WarehouseColumns::NAME, 'like', "%{$keyword}%")
                ->orWhere(WarehouseColumns::ADDRESS, 'like', "%{$keyword}%")
                ->orWhere(WarehouseColumns::PHONE, 'like', "%{$keyword}%");
        })->get();
    }

    /**
     * Search warehouses with filters (for API endpoints)
     */
    public static function searchWithFilters($filters = [])
    {
        $query = self::query();

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where(WarehouseColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(WarehouseColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(WarehouseColumns::PHONE, 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where(WarehouseColumns::IS_ACTIVE, true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where(WarehouseColumns::IS_ACTIVE, false);
            }
        }

        // Type filters
        if (!empty($filters['type'])) {
            if ($filters['type'] === 'rm') {
                $query->where(WarehouseColumns::IS_RM_WAREHOUSE, true);
            } elseif ($filters['type'] === 'fg') {
                $query->where(WarehouseColumns::IS_FG_WAREHOUSE, true);
            } elseif ($filters['type'] === 'both') {
                $query->where(WarehouseColumns::IS_RM_WAREHOUSE, true)
                      ->where(WarehouseColumns::IS_FG_WAREHOUSE, true);
            }
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? WarehouseColumns::CREATED_AT;
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        return $query;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}
