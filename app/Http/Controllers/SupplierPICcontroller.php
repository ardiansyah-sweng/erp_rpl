<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPICcontroller extends Controller
{
    public function countPICModel($supplier_id)
    {
    // Validasi sederhana (pastikan supplier_id ada)
    if (!$supplier_id) {
        return response()->json([
            'success' => false,
            'message' => 'supplier_id wajib diisi.'
        ], 400);
    }

    // Hitung jumlah PIC menggunakan method countSupplierPIC di model
    $count = SupplierPic::countSupplierPIC($supplier_id);

    // Cek apakah data ditemukan atau tidak
    if (!$count) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }

    // Kembalikan hasil dalam bentuk JSON
    return response()->json([
        'success' => true,
        'supplier_id' => $supplier_id,
        'pic_count' => $count->jumlah
    ]);
    }
}