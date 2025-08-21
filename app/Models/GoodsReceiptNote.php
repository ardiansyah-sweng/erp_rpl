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

        $this->table = config('db_constants.table.grn', 'goods_receipt_notes');
        $this->fillable = array_values(config('db_constants.column.grn', [
            'po_number',
            'product_id',
            'delivery_date',
            'delivered_quantity',
            'comments'
        ]));
    }
    public static function getGoodsReceiptNote($po_number)
    {
        return self::where('po_number', $po_number)->first();
    }
    public static function updateGoodsReceiptNote($po_number, array $data)
    {
        $grn = self::getGoodsReceiptNote($po_number);
        if (!$grn) {
            return null;
        }
        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $grn->update($filteredData);
        return $grn;
    }
    public static function addGoodsReceiptNote($data)
    {
        return self::create($data);
    }
}
