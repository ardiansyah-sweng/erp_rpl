<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BillOfMaterialModel extends Model
{
    protected $table = 'bill_of_material';
    protected $primaryKey = 'bom_id';
    public $timestamps = false;

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
     public static function countBillOfMaterial()
    {
        return self::count();
    }
 public static function countItemInBom($bom_id): int
{
    return DB::table('bill_of_material_item')
        ->where('bom_id', $bom_id)
        ->count();
}
}


