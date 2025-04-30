<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SupplierPIController extends Controller
{
    public function deleteSupplierPICByID($id)
    {
        // Mengambil nama tabel dan kolom dari file konfigurasi
        $table = Config::get('db_constants.table.supplier_pic');
        $columnId = Config::get('db_constants.column.supplier_pic.id');

        // Hapus data PIC berdasarkan ID
        $deleted = DB::table($table)->where($columnId, $id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'PIC berhasil dihapus.']);
        } else {
            return response()->json(['message' => 'PIC tidak ditemukan.'], 404);
        }
    }
}
