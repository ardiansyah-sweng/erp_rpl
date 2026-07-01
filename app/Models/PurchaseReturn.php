<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table = 'purchase_returns';

    protected $fillable = [
        'return_number',
        'po_number',
        'product_id',
        'return_date',
        'quantity',
        'reason',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'quantity' => 'integer',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_number', 'po_number');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'product_id', 'sku');
    }
}
