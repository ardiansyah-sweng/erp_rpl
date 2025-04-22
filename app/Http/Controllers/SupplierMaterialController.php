<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierMaterialController extends Controller
{
    // Menampilkan form tambah supplier material
    public function showForm()
    {
        return view('supplier.material.add');
    }

    // Menyimpan data supplier material ke tabel supplier_product
    public function addSupplierMaterial(Request $request)
    {
        $validated = $request->validate([
            'product_id'        => 'required|string|size:4',
            'sku'               => 'required|string|max:50',
            'item_name'         => 'required|string|max:50',
            'measurement_unit'  => 'required|string|max:6',
            'avg_base_price'    => 'required|integer',
            'selling_price'     => 'required|integer',
            'purchase_unit'     => 'required|integer|min:0|max:255',
            'sell_unit'         => 'required|integer|min:0|max:255',
            'stock_unit'        => 'required|integer|min:0|max:255',
        ]);

        return redirect()->back()->with('success', 'Data supplier product berhasil ditambahkan!');
    }
}