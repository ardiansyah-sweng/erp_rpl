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

        return redirect()->route('Supplier.detail', ['id' => $supplier_id]);
    }


    public function getSupplierById($id)
    {
        $sup = (new Supplier())->getSupplierById($id);

        return view('Supplier.detail', compact('sup'));
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
