<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOfMaterialModel; 

class BillOfMaterialController extends Controller
{
    public function getBOMById($id)
    {
        $sup = (new BillOfMaterialModel())->getBOMById($id);
        return response()->json($sup);
    }
    public function index(Request $request)
    {
        $boms = BillOfMaterialModel::SearchOfBillMaterial($request->keywords);
        return view('bom.list', compact('boms'));
    }
}
