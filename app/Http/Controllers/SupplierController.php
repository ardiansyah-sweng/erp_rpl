<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function getSupplierById($id)
    {
        $supplier = Supplier::find($id);

        $error = null;
        if (!$supplier) {
            $error = 'Data supplier tidak ditemukan.';
        }

        return view('supplier.detail', compact('supplier', 'error'));
    }

    public function getUpdateSupplier(Request $request, $supplier_id)
    {
        $request->validate([
            'company_name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:30',
            'bank_account' => 'required|string|max:100',
        ]);
        
        $updatedSupplier = Supplier::getUpdateSupplier($supplier_id, $request->only(['company_name', 'address', 'phone_number']));
        
        return redirect()->route('supplier.detail', ['id' => $updatedSupplier->supplier_id])
                     ->with('success', 'Data Supplier berhasil diperbarui.');
    }
}