<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierPICModel extends Model
{
    use HasFactory;
    
    protected $table = 'supplier_pics';

    protected $fillable = [
        'supplier_id',
        'name',
        'phone_number',
        'email',
        'is_active',
        'avatar',
        'assigned_date',
    ];

    // Tambahkan relasi ke model Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public static function searchSupplierPic($keywords = null)
    {
        // Eager load relasi 'supplier' untuk akses company_name
        $query = self::with('supplier');

        if ($keywords) {
            $query->where('supplier_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('name', 'LIKE', "%{$keywords}%")
                  ->orWhere('phone_number', 'LIKE', "%{$keywords}%")
                  ->orWhere('email', 'LIKE', "%{$keywords}%")
                  ->orWhere('assigned_date', 'LIKE', "%{$keywords}%")
                  ->orWhere('created_at', 'LIKE', "%{$keywords}%")
                  ->orWhere('updated_at', 'LIKE', "%{$keywords}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

     public static function getSupplierPic($supplier_id)
    {
        return self::where('supplier_id', $supplier_id)
                    ->paginate(10);
    }

}
