<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function getUpdateSupplier(Request $request, $supplier_id)
    {
        // Validasi input
        $request->validate([
            'company_name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:30',
        ]);

        // Update data supplier
        $updatedSupplier = Supplier::getUpdateSupplier($supplier_id, $request->only(['company_name', 'address']));

        if (!$updatedSupplier) {
            return response()->json(['message' => 'Data Supplier Tidak Tersedia'], 404);
        }

        return response()->json([
            'message' => 'Data Supplier berhasil diperbarui',
            'data' => $updatedSupplier,
        ]);
    }


    public function getSupplierById($id)
    {
        $sup = (new Supplier())->getSupplierById($id);

        return response()->json($sup);
    }


    public function addSupplier(Request $request)
    {
        $supplier = Supplier::create([
            'supplier_id'   => $request->supplier_id,
            'company_name'  => $request->company_name,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
            'bank_account'  => $request->bank_account,
        ]);
    }
}
