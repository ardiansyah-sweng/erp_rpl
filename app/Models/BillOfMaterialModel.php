<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterialModel extends Model
{
    protected $table = 'bill_of_material';
    protected $fillable = [
        'bom_id',
        'bom_name',
        'measurement_unit',
        'total_cost',
        'active',
    ];

    public static function SearchOfBillMaterial($keywords = null)
    {
        $query = self::query();

        if ($keywords) {
            $query->where('bom_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('bom_name', 'LIKE', "%{$keywords}%")
                  ->orWhere('measurement_unit', 'LIKE', "%{$keywords}%")
                  ->orWhere('total_cost', 'LIKE', "%{$keywords}%")
                  ->orWhere('active', 'LIKE', "%{$keywords}%")
                  ->orWhere('created_at', 'LIKE', "%{$keywords}%")
                  ->orWhere('updated_at', 'LIKE', "%{$keywords}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }
    
      /**
     * Fungsi hapus BOM berdasarkan ID.
     */
    public static function deleteBillOfMaterial($id)
    {
        $bom = self::find($id);
        return $bom ? $bom->delete() : false;
    }

    /**
     * Fungsi tambah BOM baru.
     */
    public static function createBOM($data)
    {
        return self::create($data);
    }

    /**
     * Fungsi update BOM.
     */
    public static function updateBOM($id, $data)
    {
        $bom = self::find($id);
        return $bom ? $bom->update($data) : false;
    }

    /**
     * Fungsi ambil data berdasarkan ID.
     */
    public static function getById($id)
    {
        return self::find($id);
    }

}
