<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\BranchColumns;

class Branch extends Model
{
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
            $query->where(BranchColumns::BRANCH_NAME, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::BRANCH_ADDRESS, 'LIKE', "%{$search}%")
                  ->orWhere(BranchColumns::BRANCH_TELEPHONE, 'LIKE', "%{$search}%");
        }

        return $query->orderBy(BranchColumns::CREATED_AT, 'asc')->paginate(10);
    }

    public static function addBranch($data)
    {
        return self::create($data);
    }

    public function getBranchById($id)
    {
        return self::where(BranchColumns::ID, $id)->first();
    }

    public static function getRandomBranchID()
    {
        return self::inRandomOrder()->first()->id;
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
            'aktif' => self::where(BranchColumns::BRANCH_STATUS, 1)->count(),
            'nonaktif' => self::where(BranchColumns::BRANCH_STATUS, 0)->count(),
        ];
    }
}
