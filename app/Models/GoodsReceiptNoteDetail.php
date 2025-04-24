<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNoteDetail extends Model
{
    protected $table;
    protected $fillable = [];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.grn_detail');
        $this->fillable = array_values(config('db_constants.column.grn_detail') ?? []);
    }

    public function goodsReceiptNote()
    {
        return $this->belongsTo(GoodsReceiptNote::class, config('db_constants.column.grn_detail.grn_number'), config('db_constants.column.grn.grn_number'));
    }

    public function product()
    {
         return $this->belongsTo(Item::class, config('db_constants.column.grn_detail.product_id'), config('db_constants.column.item.sku'));
    }
}