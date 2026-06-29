<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    
    protected $table = 'purchase_order_detail';
    protected $fillable = ['po_number','product_id','base_price','quantity','amount','received_days','created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id', 'product_id');
    }
}