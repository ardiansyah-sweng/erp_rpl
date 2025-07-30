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

    public function searchSuppliers(Request $request)
    {
        $keywords = $request->input('keywords');

    // Gunakan method yang sudah didefinisikan di model
        $results = Supplier::getSupplierByKeywords($keywords);

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);
    }

    public function getSupplier()
    {
        $suppliers = Supplier::withCount([
            'supplierPic as pic_count',
            'purchaseOrder as po_count'
        ])->get();

        return view('supplier.list', compact('suppliers'));
    }

}

