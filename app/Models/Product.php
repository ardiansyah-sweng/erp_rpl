<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;

class Product extends Model
{
    use HasDynamicColumns;

    protected $table = 'product';
    protected $fillable = ['product_id','name','category_id','description','measurement_unit'];
}
