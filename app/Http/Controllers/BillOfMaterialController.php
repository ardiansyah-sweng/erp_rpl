<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    // Fungsi untuk menghapus Bill of Material berdasarkan id
    public function deleteBillOfMaterial($id)
    {
        // Hapus data berdasarkan id
        $deleted = DB::table('bill_of_material')->where('id', $id)->delete();

        // Response JSON
        if ($deleted) {
            return response()->json(['message' => 'Bill of Material deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }
    }

    public function getBomDetail($id)
    {
        $bom = DB::table('bill_of_material')->where('id', $id)->first();

        if (!$bom) {
            return abort(404, 'Bill of Material tidak ditemukan');
        }

        $details = DB::table('bom_detail')
            ->where('bom_id', $bom->bom_id)
            ->select('id', 'bom_id', 'sku', 'quantity', 'cost', 'created_at', 'updated_at')
            ->get();

        return response()->json([
            'id'               => $bom->id,
            'bom_id'           => $bom->bom_id,
            'bom_name'         => $bom->bom_name,
            'measurement_unit' => $bom->measurement_unit,
            'total_cost'       => $bom->total_cost,
            'active'           => $bom->active,
            'created_at'       => $bom->created_at,
            'updated_at'       => $bom->updated_at,
            'details'          => $details,
        ]);
    }
}