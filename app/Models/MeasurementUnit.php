<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.mu');
        $this->fillable = array_values(config('db_constants.column.mu') ?? []);
    }

    protected $fillable = ['id', 'unit_name'];
}
