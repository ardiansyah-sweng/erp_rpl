<?php

namespace App\Http\Controllers;

use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        // ✅ Pakai method dari Model
        $suppliers = Supplier::getAllSuppliers();
        return view('supplier.list', compact('suppliers'));
    }
}

