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
        $updatedSupplier = Supplier::updateSupplier($supplier_id, $request->only(['company_name', 'address', 'phone_number', 'bank_account'])); //Sudah sesuai pada ERP RPL

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

    public function listSuppliers()
    {
        $suppliers = Supplier::all();
        return view('supplier.list', compact('suppliers'));
    }


    public function deleteSupplierByID($id)
    {
        $result = Supplier::deleteSupplier($id);

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message']
        ], $result['success'] ? 200 : 404);
    }
    public function AddSuplier(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|string|max:10|unique:suppliers,supplier_id',
            'company_name'   => 'required|string|max:255',
            'address'        => 'required|string|max:500',
            'phone_number'   => 'required|string|max:20',
            'bank_account'   => 'required|string|max:255',
        ]);
    }
}
