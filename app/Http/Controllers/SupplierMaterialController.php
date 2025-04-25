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

    public function updateSupplierMaterial(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        // Memanggil metode updateSupplierMaterial di model
        $model = new SupplierMaterial();
        $updateResult = $model->updateSupplierMaterial($id, $validatedData);

        if ($updateResult) {
            return redirect()->back()->with('success', 'Supplier material updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update supplier material.');
        }
    }
}