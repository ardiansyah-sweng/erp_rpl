<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPIController extends Controller
{
    // Mengembalikan method yang sebelumnya terhapus
    public function getPICByID($id)
    {
        return SupplierPic::find($id);
    }

    // Ganti nama method deleteByID() → deleteSupplierPICByID()
    public function deleteSupplierPICByID($id)
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
        // method update disini untuk updatee
    }

    public function addSupplierPIC(Request $request, $supplierID)
    {
        // method addSupplierPIC
    }
}
