<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierMaterial;

class SupplierMaterialController extends Controller
{
    // Method untuk menampilkan data supplier material
    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material', ['materials' => $materials]);
    }

    // Method untuk menambahkan data supplier material
    public function addSupplierMaterial(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => 'required|string|max:50',
            'company_name'  => 'required|string|max:255',
            'product_id'    => 'required|string|max:50',
            'product_name'  => 'required|string|max:255',
            'base_price'    => 'required|numeric',
        ]);

        DB::table('supplier_product')->insert([
            'supplier_id'   => $validated['supplier_id'],
            'company_name'  => $validated['company_name'],
            'product_id'    => $validated['product_id'],
            'product_name'  => $validated['product_name'],
            'base_price'    => $validated['base_price'],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->back()->with('success', 'Data supplier product berhasil ditambahkan!');
    }
}
