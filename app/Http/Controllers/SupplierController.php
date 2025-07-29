<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function updateSupplier(Request $request, $supplier_id)
    {
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

    public function addSupplier(Request $requset){
        $requset->validate([
            'supplier_name' => 'required|string|max:6',
            'company_name' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:12',
            'bank_account' => 'required|string|max:50',
        ]);
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

}
