<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierMaterialController extends Controller
{
    // Menampilkan form tambah supplier material
    public function showForm()
    {
        return view('supplier.material.add');
    }

    // Validasi data supplier material
    public function addSupplierMaterial(Request $request)
    {
        $validated = $request->validate([
            'product_id'        => 'required|string|size:4',
            'sku'               => 'required|string|max:50',
            'item_name'         => 'required|string|max:50',
            'measurement_unit'  => 'required|string|max:10',
            'avg_base_price'    => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'purchase_unit'     => 'required|integer|between:0,255',
            'sell_unit'         => 'required|integer|between:0,255',
            'stock_unit'        => 'required|integer|between:0,255',
        ]);

        return redirect()->back()->with('success', 'Data supplier product berhasil divalidasi!');
    }
}