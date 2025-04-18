<?php

namespace App\Http\Controllers;

use App\Models\SupplierPICModel;

class SupplierPIController extends Controller
{
    public function deleteSupplierPICByID($id)
    {
        $deleted = SupplierPICModel::deleteSupplierPICByID($id);

        if (!$deleted) {
            return redirect()->back()->with('error', 'PIC Supplier tidak ditemukan.');
        }

        return redirect()->back()->with('success', 'PIC Supplier berhasil dihapus.');
    }
}
