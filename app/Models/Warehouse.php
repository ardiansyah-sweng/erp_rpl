<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Constants\WarehouseColumns;

class Warehouse extends Model
{
    use HasFactory;
    
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_tables.warehouse');
        $this->fillable = WarehouseColumns::getFillable();
    }

    public static function getWarehouseAll($search = null)
    {
        $query = self::query();

        if ($search) {
            $query->where(WarehouseColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(WarehouseColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(WarehouseColumns::PHONE, 'LIKE', "%{$search}%");
        }

        return $query->orderBy(WarehouseColumns::CREATED_AT, 'asc')->paginate(config('pagination.branch_per_page'));
    }

    public static function addWarehouse($data)
    {
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
     * Static method for deleting warehouse (consistent with Branch model)
     */
    public static function deleteWarehouse($id)
{
    // Cek apakah warehouse masih digunakan di tabel assortment_production
    $isUsed = DB::table('assortment_production')
        ->where('rm_whouse_id', $id)   // ✅ ganti dari fm_ jadi rm_
        ->orWhere('fg_whouse_id', $id) // ✅ ini tetap
        ->exists();

    // Jika masih digunakan, batalkan penghapusan
    if ($isUsed) {
        return [
            'status' => false,
            'message' => 'Warehouse tidak dapat dihapus karena masih digunakan di tabel assortment_production.'
        ];
    }

    // Jika tidak digunakan, lanjutkan hapus
    $deleted = self::where(WarehouseColumns::ID, $id)->delete();

    if ($deleted) {
        return [
            'status' => true,
            'message' => 'Warehouse berhasil dihapus.'
        ];
    } else {
        return [
            'status' => false,
            'message' => 'Warehouse gagal dihapus atau tidak ditemukan.'
        ];
    }
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
    }
}
