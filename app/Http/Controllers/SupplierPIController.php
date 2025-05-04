<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPICController extends Controller
{
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id);
        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $supplier = $pic->supplier;
        $pic->supplier_name = $supplier ? $supplier->name : null;
        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        // method update di sini
    }
}
