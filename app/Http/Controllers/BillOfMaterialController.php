<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function getBillOfMaterial()
    {
        $data = BillOfMaterial::getBillOfMaterial();
        return response()->json($data);
    }
}
