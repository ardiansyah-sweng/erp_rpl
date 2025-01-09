<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model
{
    protected $table = 'goods_receipt_note';
    protected $fillable = ['po_number', 'created_at'];
}
