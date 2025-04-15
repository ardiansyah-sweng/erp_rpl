<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierMaterial;

class SupplierMaterialController extends Controller
{
    public function create()
    {
        return view('supplier_material');
    }

    public function material(Request $request)
    {
        SupplierMaterial::create([
            'bom_id' => $request->bom_id,
            'bom_name' => $request->bom_name,
            'measurement_unit' => $request->measurement_unit,
            'sku' => $request->sku,
            'total_cost' => $request->total_cost,
            'active' => $request->active
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan ke tabel bill_of_material!');
    }
}
