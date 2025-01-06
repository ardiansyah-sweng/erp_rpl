<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    protected $table = 'supplier_product';
    protected $fillable = ['supplier_id','product_id','base_price'];
}
