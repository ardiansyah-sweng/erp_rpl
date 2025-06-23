<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BillOfMaterial;
use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function addBillOfMaterial(Request $request)
    {
        $validatedData = $request->validate([
            'bom_name'          => 'required|string|min:3|unique:bill_of_material,bom_name',
            'measurement_unit'  => 'required|string|max:20',
            'total_cost'        => 'required|numeric|min:0',
            'active'            => 'required|boolean',
        ]);

        // Generate bom_id dengan format BOM001, BOM002, dst.
        $lastBom = BillOfMaterial::orderBy('id', 'desc')->first();
        $nextId = $lastBom ? ((int)substr($lastBom->bom_id, -3) + 1) : 1;
        $validatedData['bom_id'] = 'BOM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        BillOfMaterial::addBillOfMaterial($validatedData);

        return redirect()->back()->with('success', 'Bill of Material berhasil ditambahkan!');
    }
}
