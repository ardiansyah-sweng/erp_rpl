<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPICModel extends Model
{
    protected $table = 'supplier_pic';

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

}
