<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic; // menghubungkan supplierPic.php
use Illuminate\Http\JsonResponse;

class SupplierPIController extends Controller
{
    public function deleteSupplierPICByID($id): JsonResponse
    {
        $deleted = SupplierPic::deleteSupplierPICByID($id);

        if ($deleted) {
            return response()->json(['message' => 'PIC berhasil dihapus.']);
        } else {
            return response()->json(['message' => 'PIC tidak ditemukan.'], 404);
        }
    }
}
