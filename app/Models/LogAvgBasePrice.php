<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAvgBasePrice extends Model
{
    protected $table = 'log_avg_base_price';
    protected $fillable = ['product_id', 'old_avg_base_price', 'new_avg_base_price', 'created_at', 'updated_at'];
}
