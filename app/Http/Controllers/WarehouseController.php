<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function getWarehouseById($id)
    {
        $warehouse = (new Warehouse())->getWarehouseByID($id);

        $items = [
            [
                'id' => 1,
                'name' => 'Item A',
                'quantity' => 100,
                'location' => 'Rak 1',
                'comments' => 'Barang masuk awal bulan.',
                'created_at' => '2025-07-01 10:00:00',
            ],
            [
                'id' => 2,
                'name' => 'Item B',
                'quantity' => 50,
                'location' => 'Rak 2',
                'comments' => 'Stok dari supplier X.',
                'created_at' => '2025-07-02 11:30:00',
            ],
            [
                'id' => 3,
                'name' => 'Item C',
                'quantity' => 200,
                'location' => 'Rak 3',
                'comments' => 'Barang retur.',
                'created_at' => '2025-07-03 09:15:00',
            ],
        ];

        if (!$warehouse) {
            // Dummy warehouse for not found
            $warehouse = [
                'id' => $id,
                'warehouse_name' => 'Warehouse Dummy',
                'is_active' => true,
                'last_updated_status' => 'N/A',
                'created_at' => '-',
                'updated_at' => '-',
            ];
        }

        return view('warehouse.detail', compact('warehouse', 'items'));
    }

    public function countWarehouse()
    {
        $total = Warehouse::countWarehouse();

        return response()->json([
            'total_warehouse' => $total
        ]);
    }

    public function searchWarehouse(Request $request)
    {
        $keyword = $request->input('keyword');
        $warehouses = (new Warehouse())->searchWarehouse($keyword);

        if ($warehouses->isEmpty()) {
            return response()->json(['message' => 'Tidak ada warehouse yang ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $warehouses
        ]);
    }

    public function deleteWarehouse($id)
    {
        return (new Warehouse)->deleteWarehouse($id);
    }

    public function exportPdf()
    {
        $warehouse = [
            [
                'id' => 1,
                'warehouse_name' => 'Warehouse A',
                'warehouse_address' => 'Location A',
                'warehouse_telephone' => '1234567890',
                'is_active' => true,
                'created_at' => '2023-01-01',
                'updated_at' => '2023-01-02',
            ],
            [
                'id' => 2,
                'warehouse_name' => 'Warehouse B',
                'warehouse_address' => 'Location B',
                'warehouse_telephone' => '1234567890',
                'is_active' => false,
                'created_at' => '2023-02-01',
                'updated_at' => '2023-04-01',
            ],
            [
                'id' => 3,
                'warehouse_name' => 'Warehouse C',
                'warehouse_address' => 'Location C',
                'warehouse_telephone' => '1234567890',
                'is_active' => true,
                'created_at' => '2023-01-06',
                'updated_at' => '2023-01-04',
            ],
        ];

        $pdf = Pdf::loadView('warehouse.report', compact('warehouse'));
        return $pdf->stream('warehouse_report.pdf');
    }

    public function getWarehouseAll()
    {
        $warehouses = Warehouse::getWarehouseAll();
        return view('warehouse.list', compact('warehouses'));
    }
  
    public function addWarehouse(Request $request)
    {
        $data = $request->all(); 
        $validator = Validator::make($data, [
            'warehouse_name' => 'required|min:3|unique:warehouse,warehouse_name',
            'warehouse_address' => 'required',
            'warehouse_telephone' => 'required',
            'is_rm_whouse' => 'required|boolean',
            'is_fg_whouse' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
            ];
        }

        Warehouse::addWarehouse($data);

        return [
            'success' => true,
            'message' => 'Warehouse berhasil ditambahkan.',
        ];
    }
}
