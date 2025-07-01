<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BillOfMaterialController extends Controller
{
    // public function getBomById($id)
    // {
    //     // Data dummy sesuai dengan isi tabel bill_of_material
    //     $dummyBomList = [
    //         1 => [
    //             'id' => 1,
    //             'bom_id' => 'BOM-001',
    //             'bom_name' => 'BOM-BOM-001',
    //             'measurement_unit' => 31,
    //             'total_cost' => 38489,
    //             'active' => 1,
    //             'created_at' => '2025-06-17 09:24:24',
    //             'updated_at' => '2025-06-17 09:24:24'
    //         ],
    //         2 => [
    //             'id' => 2,
    //             'bom_id' => 'BOM-002',
    //             'bom_name' => 'BOM-BOM-002',
    //             'measurement_unit' => 31,
    //             'total_cost' => 132370,
    //             'active' => 1,
    //             'created_at' => '2025-06-17 09:24:24',
    //             'updated_at' => '2025-06-17 09:24:24'
    //         ],
    //     ];

    //     $bom = $dummyBomList[$id] ?? null;

    //     if (!$bom) {
    //         return abort(404, 'Bill of Material tidak ditemukan');
    //     }

    //     return response()->json($bom);

    // }
        // public function getBomById($id)
        //     {
        //         $bom = DB::table('bill_of_material')->where('id', $id)->first();

        //         if (!$bom) {
        //             return abort(404, 'Bill of Material tidak ditemukan');
        //         }

        //         return response()->json($bom);
        //     }
        public function getBomById(Request $request)
    {
        $id = $request->input('id'); // contoh: /api/bom?id=1
        $bom_id = $request->input('bom_id'); // contoh: /api/bom?bom_id=BOM-001

        if ($id) {
            $bom = DB::table('bill_of_material')->where('id', $id)->first();
        } elseif ($bom_id) {
            $bom = DB::table('bill_of_material')->where('bom_id', $bom_id)->first();
        } else {
            $bom = DB::table('bill_of_material')->inRandomOrder()->first();
        }

        if (!$bom) {
            return abort(404, 'Bill of Material tidak ditemukan');
        }

        return response()->json($bom);
    }
}

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
