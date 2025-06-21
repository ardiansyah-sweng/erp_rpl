<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOfMaterial;
use App\Models\BomDetail; 

class BillOfMaterialController extends Controller
{
    public function deleteBillOfMaterial($id)
    {
        $bom = BillOfMaterial::find($id);

        if (!$bom) {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }

        BomDetail::where('bill_of_material_id', $id)->delete();

        $bom->delete();

        return response()->json(['message' => 'Bill of Material dan detail berhasil dihapus.'], 200);
    }
}