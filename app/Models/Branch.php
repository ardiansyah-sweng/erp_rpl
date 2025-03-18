<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class Branch extends Model
{
    use HasDynamicColumns;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom sesuai dengan konfigurasi
        $this->table = config('db_constants.table.branch');
        $this->fillable = array_values(config('db_constants.column.branch') ?? []);
    }

    public function getBranchById($id)
    {
        return self::where('id', $id)->first();
    }

    public function addBranch($data)
    {
        return self::create($data);
    }

    public function updateBranch($id, $data)
    {
        $branch = self::findOrFail($id);
        $branch->update($data);
        return $branch;
    }

    public function deleteBranch($id)
    {
        return self::where('id', $id)->delete();
    }

    public static function getRandomBranchID()
    {
        return self::inRandomOrder()->first()->id;
    }
}
