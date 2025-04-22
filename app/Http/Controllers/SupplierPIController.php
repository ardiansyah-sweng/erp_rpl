<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPIController extends Controller
{
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id);

        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $pic->supplier_name = $pic->name;
        return view('supplier.detail', ['pic' => $pic]);
    }

    // disini method update 
    public function update(Request $request, $id)
    {
        //isi method update disini
    }
}
