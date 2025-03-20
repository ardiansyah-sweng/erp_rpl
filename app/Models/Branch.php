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

    public static function addBranch($name, $address, $telephone, $status = 1)
    {
        return self::create([
            'branch_name' => $name,
            'branch_address' => $address,
            'branch_telephone' => $telephone,
            'branch_status' => $status,
        ]);
    }
}
