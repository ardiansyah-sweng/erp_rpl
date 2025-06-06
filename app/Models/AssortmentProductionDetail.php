<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssortmentProductionDetail extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.assort_prodetail');
        $this->fillable = array_values(config('db_constants.column.assort_prodetail') ?? []);
    }
}
