<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPIController extends Controller
{
    public function deleteByID($id)
{
    $pic = SupplierPic::find($id);

    if (!$pic) {
        return redirect()->route('supplier.pic.list')->with('error', 'PIC tidak ditemukan.');
    }

    $pic->delete();
    return redirect()->route('supplier.pic.list')->with('success', 'PIC berhasil dihapus.');
}

    public function update(Request $request, $id)
    {
        
    }
}
