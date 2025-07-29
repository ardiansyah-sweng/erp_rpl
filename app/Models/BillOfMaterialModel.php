<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class BillOfMaterialModel extends Model
{
    protected $table = 'bill_of_material';

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

    public static function deleteBom($id)
    {
        $bom = self::find($id);

        if ($bom) {
        return $bom->delete();
        }

        return false;
    }
    
    public static function countBillOfMaterial()
    {
        return self::count();
    }

    
    public static function getBomDetail($id)
    {
        $bom = DB::table('bill_of_material')->where('id', $id)->first();

        if (!$bom) {
            return null;
        }

        $details = DB::table('bom_detail')
            ->where('bom_id', $bom->bom_id)
            ->select('id', 'bom_id', 'sku', 'quantity', 'cost', 'created_at', 'updated_at')
            ->get();

        return [
            'id'               => $bom->id,
            'bom_id'           => $bom->bom_id,
            'bom_name'         => $bom->bom_name,
            'measurement_unit' => $bom->measurement_unit,
            'total_cost'       => $bom->total_cost,
            'active'           => $bom->active,
            'created_at'       => $bom->created_at,
            'updated_at'       => $bom->updated_at,
            'details'          => $details,
        ];
    }
}
