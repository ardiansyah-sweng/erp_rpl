<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPICController extends Controller
{
    public function addSupplierPIC(Request $request, $supplierID)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:30',
            'email' => 'required|email|max:50',
            'assigned_date' => 'required|date',
        ]);

        // Simpan data menggunakan model SupplierPic
        SupplierPic::addSupplierPIC($supplierID, $validatedData);

        return redirect()->back()->with('success', 'PIC berhasil ditambahkan!');
    }
}
