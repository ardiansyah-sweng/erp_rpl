<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogBasePrice extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.log_base_price_supplier');
        $this->fillable = array_values(config('db_constants.column.log_base_price_supplier') ?? []);
    }
}
