<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class SupplierProduct extends Model
{
    // protected $table = 'supplier_product';
    // protected $fillable = ['id','supplier_id','product_id','base_price'];
    use HasDynamicColumns;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier_product');
        $this->fillable = array_values(config('db_constants.column.supplier_product') ?? []);
    }
}
