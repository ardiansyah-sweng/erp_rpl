<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPICController extends Controller
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

        // Hitung jumlah PIC menggunakan method di model
        $count = SupplierPic::countSupplierPIC($supplier_id);

        // Jika null (misalnya supplier_id tidak ditemukan)
        if (is_null($count)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // Jika hasilnya angka (misalnya: 0 atau lebih)
        return response()->json([
            'success' => true,
            'supplier_id' => $supplier_id,
            'pic_count' => $count
        ]);
    }
}
