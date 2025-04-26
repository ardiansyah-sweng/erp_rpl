<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPICModel extends Model

{
    protected $table = 'supplier_pic'; // nama tabel sesuai database

    public static function searchSupplierPic($keywords = null)
    {
        $query = self::query();

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