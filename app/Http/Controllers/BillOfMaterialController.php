<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    // Fungsi untuk menghapus Bill of Material berdasarkan id
    public function deleteBillOfMaterial($id)
    {
        // Hapus data berdasarkan id
        $deleted = DB::table('bill_of_material')->where('id', $id)->delete();

        // Response JSON
        if ($deleted) {
            return response()->json(['message' => 'Bill of Material deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }
    }
}