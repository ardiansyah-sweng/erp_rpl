<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SupplierPic;

class SupplierPIController extends Controller
{
    // Menghapus data PIC berdasarkan ID
    public function deleteSupplierPICByID($id): JsonResponse
    {
        $deleted = SupplierPic::deleteSupplierPICByID($id);

        if ($deleted) {
            return response()->json(['message' => 'PIC berhasil dihapus.']);
        } else {
            return response()->json(['message' => 'PIC tidak ditemukan.'], 404);
        }
    }

    // Mendapatkan data PIC berdasarkan ID
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id); // memanggil method getPICByID dari model SupplierPic

        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $supplier = $pic->supplier;
        $pic->supplier_name = $supplier ? $supplier->name : null;

        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

    // Update data PIC
    public function update(Request $request, $id)
    {
       
    }
}
