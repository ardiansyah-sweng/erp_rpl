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
        $updatedSupplier = Supplier::getUpdateSupplier($supplier_id, $request->only(['company_name', 'address', 'phone_number']));

        if (!$updatedSupplier) {
            return redirect()->back()->with('error', 'Data Supplier tidak tersedia.');
        }

        return redirect()->route('supplier.detail', ['id' => $updatedSupplier->supplier_id])
                     ->with('success', 'Data Supplier berhasil diperbarui.');
    }
    public function getSupplierById($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return redirect()->back()->with('error', 'Data supplier tidak ditemukan.');
        }

        return view('supplier.detail', compact('supplier'));
    }
}