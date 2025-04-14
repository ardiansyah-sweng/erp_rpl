<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierMaterialController extends Controller
{
    public function getSupplierById($id)
    {
    $supplier = (new Supplier())->getSupplierByID($id);

    if (!$supplier) {
        return abort(404, 'Cabang tidak ditemukan');
    }

    return view('supplier.detail', compact('supplier'));
    }

    public function getSupplierAll(Request $request)
    {
        $search = $request->input('search');
        $branches = Supplier::getAllSupplier($search);
        return view('Supplier.list', ['suppliers' => $suppliers]);
    }

    public function addSupplier(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|min:3|unique:supplier,company_name',
            'supplier_address' => 'required|string|min:3',
            'supplier_telephone' => 'required|string|min:3',
            'bank_account' => 'required|string|min:3|unique:supplier,bank_account'
        ]);

        $supplier = new Supplier();
        $supplier->addSupplier([
            'company_name' => $request->company_name,
            'supplier_address' => $request->supplier_address,
            'supplier_telephone' => $request->supplier_telephone,
            'bank_account' => $request->bank_account
        ]);

        return redirect()->route('supplier.list')->with('success', 'Cabang berhasil ditambahkan!');
    }
}
