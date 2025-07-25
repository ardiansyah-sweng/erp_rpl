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

        if (!$warehouse) {
            return abort(404, 'Warehouse tidak ditemukan');
        }

        return response()->json($warehouse);
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
        $isUsed = DB::table('assortment_production')
            ->where('rm_whouse_id', $id)
            ->orWhere('fg_whouse_id', $id)
            ->exists();

        if ($isUsed) {
            return redirect()->back()->with('error', 'Warehouse tidak bisa dihapus karena sedang digunakan di produksi.');
        }

        // Hapus langsung dari tabel warehouse
        $deleted = DB::table('warehouse')->where('id', $id)->delete();

        if ($deleted) {
            return redirect()->back()->with('success', 'Warehouse berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Warehouse tidak ditemukan atau gagal dihapus.');
        }
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

    public function addWarehouse($data)
    {
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
