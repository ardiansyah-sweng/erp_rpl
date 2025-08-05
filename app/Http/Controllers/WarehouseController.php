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
            abort(404, 'Warehouse tidak ditemukan');
        }
        return view('warehouse.detail', compact('warehouse'));
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
        $warehouse = Warehouse::getWarehouseAll();
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