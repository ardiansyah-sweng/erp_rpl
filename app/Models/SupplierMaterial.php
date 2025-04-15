<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaterial extends Model
{
    protected $table = 'bill_of_material'; 
    protected $fillable = [
        'bom_id',
        'bom_name',
        'measurement_unit',
        'sku',
        'total_cost',
        'active'
    ];

    public $timestamps = true; 
}
