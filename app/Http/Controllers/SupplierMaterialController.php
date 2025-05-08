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
        SupplierMaterial::addSupplierMaterial($request);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan ke tabel Supplier Product!');
    }

    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material.list', ['materials' => $materials]);
    }
     public function addSupplierMaterial(Request $request)
     {
        $validated = $request->validate([
            'supplier_id'   => 'required|string|size:6',
            'company_name'  => 'required|string|max:255', 
            'product_id'    => 'required|string|max:50',
            'product_name'  => 'required|string|max:255',
            'base_price'    => 'required|integer|min:0',
            'created_at'    => 'nullable|date',
            'updated_at'    => 'nullable|date',
        ]);
         return redirect()->back()->with('success', 'Data supplier product berhasil divalidasi!');
     }
     
}

