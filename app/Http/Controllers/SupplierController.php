<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function getUpdateSupplier(Request $request, $supplier_id)
    {
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

    public function addSupplier(Request $requset){
        $requset->validate([
            'supplier_name' => 'required|string|max:6',
            'company_name' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:12',
            'bank_account' => 'required|string|max:50',
        ]);
    }
}