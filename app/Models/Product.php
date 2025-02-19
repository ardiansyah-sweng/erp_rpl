<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class Product extends Model
{
    use HasDynamicColumns;

    #protected $fillable = ['product_id','name','category_id','description','measurement_unit'];

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.product');
        $this->fillable = array_values(config('db_constants.column.product') ?? []);
    }
}
