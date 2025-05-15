<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model
{
    protected $table = 'goods_receipt_notes';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_number',
        'product_id',
        'delivery_date',
        'delivered_quantity',
        'comments',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function purchaseOrder()
    {
         return $this->belongsTo(PurchaseOrder::class, 'po_number', config('db_constants.column.po.po_number', 'po_number'));
    }
}