<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Update data supplier berdasarkan ID
     */
    public function getUpdateSupplier(Request $request, $supplier_id)
    {
        // Validasi input
        $request->validate([
            'company_name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:30',
        ]);

        // Update data supplier
        $updatedSupplier = Supplier::getUpdateSupplier($supplier_id, $request->only([
            'company_name',
            'address',
            'phone_number'
        ]));

        if (!$updatedSupplier) {
            return response()->json(['message' => 'Data Supplier Tidak Tersedia'], 404);
        }

        return response()->json([
            'message' => 'Data Supplier berhasil diperbarui',
            'data' => $updatedSupplier,
        ]);
    }

    /**
     * Ambil data supplier berdasarkan ID
     */
    public function getSupplierById($id)
    {
        $supplier = Supplier::getSupplierById($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier tidak ditemukan'], 404);
        }

        return response()->json($supplier);
    }
}
#ttff