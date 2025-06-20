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
        public function getBomById($id)
            {
                $bom = DB::table('bill_of_material')->where('id', $id)->first();

                if (!$bom) {
                    return abort(404, 'Bill of Material tidak ditemukan');
                }

                return response()->json($bom);
            }
}
