<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPIC;

class SupplierPIController extends Controller
{
    public function deleteSupplierPICByID($id)
    {
        $pic = SupplierPIC::find($id);

        if (!$pic) {
            return redirect()->back()->with('error', 'PIC Supplier tidak ditemukan.');
        }

        $pic->delete();

        return redirect()->back()->with('success', 'PIC Supplier berhasil dihapus.');
    }
}
