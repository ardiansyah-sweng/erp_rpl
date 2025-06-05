<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOfMaterial;

class BillOfMaterialController extends Controller
{
    public function deleteBillOfMaterial($id)
    {
        $bom = BillOfMaterial::find($id);

        if (!$bom) {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }

        $bom->delete();

        return response()->json(['message' => 'Bill of Material deleted successfully.'], 200);
    }
}
