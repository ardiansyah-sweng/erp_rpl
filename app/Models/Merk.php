<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.merk');
        $this->fillable = array_values(config('db_constants.column.merk') ?? []);
    }

    public static function countMerek()
    {
        return self::count();
    }
    public function getMerkById($id)
    {
        return self::where('id', $id)->first();
    }
}
