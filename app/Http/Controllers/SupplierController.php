<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function updateSupplier(Request $request, $supplier_id)
    {
        // Validasi input
        $request->validate([
            'company_name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:30',
            'bank_account' => 'required|string|max:100',
        ]);

        // Update data supplier nama perusahaan, alamat, nomor telepon dan akun bank
        $updatedSupplier = Supplier::updateSupplier($supplier_id, $request->only(['company_name','address','phone_number','bank_account']));//Sudah sesuai pada ERP RPL

        return $updatedSupplier;
    }
    public function getSupplierById($id)
    {
        $sup = (new Supplier())->getSupplierById($id);

        return response()->json($sup);
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
