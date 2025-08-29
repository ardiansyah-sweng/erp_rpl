<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\BranchColumns;

class Branch extends Model
{
    use HasFactory;
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_tables.branch');
        $this->fillable = BranchColumns::getFillable();
    }

    public static function getAllBranch($search = null)
    {
        $query = self::query();

        if ($search) {
            $query->where(BranchColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::PHONE, 'LIKE', "%{$search}%");
        }

        return $query->orderBy(BranchColumns::CREATED_AT, 'asc')->paginate(10);
    }

    public static function addBranch($data)
    {
        return self::create($data);
    }

    public static function getBranchById($id)
    {
        return self::where(BranchColumns::ID, $id)->first();
    }

    public static function getRandomBranchID()
    {
        $branch = self::inRandomOrder()->first();
        return $branch ? $branch->id : null;
    }

    public static function updateBranch($id, $data)
    {
        $branch = self::find($id);
        if ($branch) {
            $branch->update($data);
            return true;
        }
        return false;
    }
    
    public static function findBranch($id)
    {
        $branch = self::find($id);
        if (!$branch) {
            throw new \Exception('Cabang tidak ditemukan!');
        }
        return $branch;
    }

    public static function deleteBranch($id)
    {
        return self::where(BranchColumns::ID, $id)->delete();
    }

    public static function countBranch()
    {
        return self::count();
    }

    public static function countBranchByStatus()
    {
        return [
            'aktif' => self::where(BranchColumns::IS_ACTIVE, 1)->count(),
            'nonaktif' => self::where(BranchColumns::IS_ACTIVE, 0)->count(),
        ];
    }

    /**
     * Get active branches only
     */
    public static function getActiveBranches()
    {
        return self::where(BranchColumns::IS_ACTIVE, 1)
                   ->orderBy(BranchColumns::NAME, 'asc')
                   ->get();
    }

    /**
     * Check if branch name exists (for validation)
     */
    public static function nameExists($name, $exceptId = null)
    {
        $query = self::where(BranchColumns::NAME, $name);
        
        if ($exceptId) {
            $query->where(BranchColumns::ID, '!=', $exceptId);
        }
        
        return $query->exists();
    }

    /**
     * Advanced search with filters for API endpoints
     * Best Practice: Query logic in Model, not Controller
     */
    public static function searchWithFilters(array $filters = [])
    {
        $query = self::query();

        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where(BranchColumns::NAME, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::PHONE, 'LIKE', "%{$search}%");
            });
        }

        // Status filtering
        if (isset($filters['status'])) {
            $status = $filters['status'];
            if ($status === 'active') {
                $query->where(BranchColumns::IS_ACTIVE, true);
            } elseif ($status === 'inactive') {
                $query->where(BranchColumns::IS_ACTIVE, false);
            }
        }

        // Individual field searches
        if (!empty($filters['name'])) {
            $query->where(BranchColumns::NAME, 'LIKE', '%' . $filters['name'] . '%');
        }
        
        if (!empty($filters['address'])) {
            $query->where(BranchColumns::ADDRESS, 'LIKE', '%' . $filters['address'] . '%');
        }
        
        if (!empty($filters['phone'])) {
            $query->where(BranchColumns::PHONE, 'LIKE', '%' . $filters['phone'] . '%');
        }
        
        if (isset($filters['is_active'])) {
            $query->where(BranchColumns::IS_ACTIVE, $filters['is_active']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? BranchColumns::CREATED_AT;
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        $validSortFields = [
            BranchColumns::NAME,
            BranchColumns::ADDRESS,
            BranchColumns::CREATED_AT,
            BranchColumns::IS_ACTIVE
        ];
        
        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }

    /**
     * Get active branches with pagination support
     */
    public static function getActiveBranchesPaginated($perPage = 15)
    {
        return self::where(BranchColumns::IS_ACTIVE, true)
                   ->orderBy(BranchColumns::CREATED_AT, 'desc')
                   ->paginate($perPage);
    }

    /**
     * Get branch statistics
     */
    public static function getStatistics()
    {
        $total = self::count();
        $active = self::where(BranchColumns::IS_ACTIVE, true)->count();
        $inactive = $total - $active;

        return [
            'total_branches' => $total,
            'active_branches' => $active,
            'inactive_branches' => $inactive,
            'active_percentage' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }
}
