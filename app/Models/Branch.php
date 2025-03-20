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

    public function getBranchById($id){
        return self::where('id', $id)->first();
    }

    public static function getRandomBranchID(){
        return self::inRandomOrder()->first()->id;
    }

    public static function getAllBranch()
    {
        return self::orderBy('created_at', 'desc')->paginate(10);
    }
}
 