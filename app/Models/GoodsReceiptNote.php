<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model
{
    protected $table = 'grn';
    protected $fillable = ['id', 'po_number', 'product_id', 'delivery_date', 'delivered_quantity', 'comments', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';
    public $incrementing = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.grn');
        $this->fillable = array_values(config('db_constants.column.grn') ?? []);
    }
}
