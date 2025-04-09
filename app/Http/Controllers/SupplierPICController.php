<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierPICController extends Controller
{
    public function addSupplierPIC(Request $request, $supplierID)
    {
        

        return redirect()->back()->with('success', 'PIC berhasil ditambahkan!');
    }
}