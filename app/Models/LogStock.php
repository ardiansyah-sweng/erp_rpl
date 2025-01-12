<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStock extends Model
{
    protected $table = 'log_stock';
    protected $fillable = ['log_id','product_id', 'old_stock', 'new_stock', 'created_at', 'updated_at'];
}
