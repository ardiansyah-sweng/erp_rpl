<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.bom');
        $this->fillable = array_values(config('db_constants.column.bom') ?? []);
    }

    public function addBillOfMaterial($data)
    {
        return self::create($data);
    }
}
