<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogBasePrice extends Model
{
    protected $table = 'log_base_price_supplier_product';
    protected $fillable = ['supplier_id','product_id', 'old_base_price', 'new_base_price', 'created_at', 'updated_at'];
}
