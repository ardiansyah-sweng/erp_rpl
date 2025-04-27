<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierMaterial;

class SupplierMaterialController extends Controller
{
    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material', ['materials' => $materials]);
    }

    public function updateSupplierMaterial(Request $request, $supplier_id, $product_id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:100',
            'product_name' => 'required|string|max:50',
            'base_price' => 'required|integer|min:0',
        ]);

        // Memanggil metode updateSupplierMaterial di model
        $model = new SupplierMaterial();
        $updateResult = $model->updateSupplierMaterial($supplier_id, $product_id, $validatedData);

        if ($updateResult) {
            return redirect()->back()->with('success', 'Supplier material updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update supplier material.');
        }
    }
}