<?php

namespace App\Http\Controllers;

use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all(); // otomatis pakai tabel & kolom dari config
        return view('supplier.list', compact('suppliers'));
    }
}

