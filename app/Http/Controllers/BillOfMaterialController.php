<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function getBomById($id)
    {
        // Data dummy sesuai dengan isi tabel bill_of_material
        $dummyBomList = [
            1 => [
                'id' => 1,
                'bom_id' => 'BOM-001',
                'sku' => 'P006-ex',
                'quantity' => 9,
                'cost' => 2779,
                'created_at' => '2025-06-17 16:57:24',
                'updated_at' => '2025-06-17 16:57:24'
            ],
            2 => [
                'id' => 2,
                'bom_id' => 'BOM-002',
                'sku' => 'P020-ducimus',
                'quantity' => 8,
                'cost' => 2978,
                'created_at' => '2025-06-17 16:57:24',
                'updated_at' => '2025-06-17 16:57:24'
            ],
        ];

        $bom = $dummyBomList[$id] ?? null;

        if (!$bom) {
            return abort(404, 'Bill of Material tidak ditemukan');
        }

        return response()->json($bom);

    }
}
