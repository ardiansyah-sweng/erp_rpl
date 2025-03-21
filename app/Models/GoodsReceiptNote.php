<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.grn');
        $this->fillable = array_values(config('db_constants.column.grn') ?? []);
    }
}
