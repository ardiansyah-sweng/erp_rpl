<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class Branch extends Model
{
    use HasDynamicColumns;

    protected $table;
    protected $fillable = [];
    protected $guarded = [];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.branch');
        $this->fillable = array_values(config('db_constants.column.branch') ?? []);
    }

    public function getBranchById($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getRandomBranchID()
    {
        return self::inRandomOrder()->first()->id;
    }

    public static function getAllBranch($search = null)
    {
        $query = self::query();

        if ($search) {
            $query->where('branch_name', 'LIKE', "%{$search}%")
                  ->orWhere('branch_address', 'LIKE', "%{$search}%")
                  ->orWhere('branch_telephone', 'LIKE', "%{$search}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

    public static function addBranch($data)
    {
        return self::create($data);
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
        return self::where('id', $id)->delete();
    }
main

    public static function countBranch()
    {
        return self::count();
    }

    public static function countBranchByStatus()
    {
        return [
            'aktif' => self::where('branch_status', 1)->count(),
            'nonaktif' => self::where('branch_status', 0)->count(),
            ];
    }
}
