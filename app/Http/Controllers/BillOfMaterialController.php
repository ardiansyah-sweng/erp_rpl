<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function addBillOfMaterial(Request $request)
    {
        $request->validate([
            'bom_name' => 'required|string|min:3|unique:bill_of_materials,bom_name',
            'measurement_unit' => 'required|string|max:20',
            'total_cost' => 'required|numeric|min:0',
            'active' => 'required|boolean'
        ]);

        $billOfMaterial->create([
            'bom_name' => $request->bom_name,
            'measurement_unit' => $request->measurement_unit,
            'total_cost' => $request->total_cost,
            'active' => $request->active,
        ]);

        return redirect()->route('billofmaterial.list')->with('success', 'Bill of Material berhasil ditambahkan!');
    }

}
