<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPICController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|integer',
            'name'        => 'required|string|max:255',
            'position'    => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
        ]);

        // Ini cara yang benar: memanggil fungsi dari model
        SupplierPic::addSupplierPIC($validatedData);

        return redirect()->back()->with('success', 'PIC Supplier berhasil ditambahkan!');
    }
}
