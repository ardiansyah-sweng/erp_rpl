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
        SupplierMaterial::storeMaterial($request);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan ke tabel bill_of_material!');
    }
}
