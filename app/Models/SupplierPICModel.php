<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPICModel extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'supplier_pic';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'supplier_id', 
        'name', 
        'email', 
        'phone', 
        'position'
    ];

    public static function countSupplierPIC($supplierID)
    {
        return self::where('supplier_id', $supplierID)->count();
    }
    // ------------------------------
}