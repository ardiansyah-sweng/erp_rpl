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
    
    public function deleteSupplierByID($id)
    {
        $result = Supplier::deleteSupplier($id);

        if ($result === true) {
            return redirect()->back()->with('success', 'Supplier berhasil dihapus');
            
        } elseif ($result === 'Supplier ini tidak bisa dihapus karena sudah memiliki purchase order') {
            return redirect()->back()->with('error', $result);
        }

        return redirect()->back()->with('error', 'Supplier tidak ditemukan');
    }

}
