<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{

    public function getWarehouseById($id)
    {
        $warehouse = (new Warehouse())->getWarehouseByID($id);

        if (!$warehouse) {
            return abort(404, 'Gudang tidak ditemukan');
        }

        return view('whouse.detail', compact('warehouse'));
    }

    public function getWarehouseAll(Request $request)
    {
        $search = $request->input('search');
        $warehouses = Warehouse::getAllWarehouse($search);

        if ($request->has('export') && $request->input('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('whouse.report', ['warehouses' => $warehouses]);
            return $pdf->stream('report-warehouse.pdf');
        }

        return view('whouse.list', ['warehouses' => $warehouses]);
    }

    public function create()
    {
        return view('whouse.add');
    }

    public function addWarehouse(Request $request)
    {
        $request->validate([
            'warehouse_name' => 'required|string|min:3|unique:warehouse,warehouse_name',
            'warehouse_address' => 'required|string|min:3',
            'warehouse_telephone' => 'required|string|min:3'
        ]);

        $warehouse = new Warehouse();
        $warehouse->addWarehouse([
            'warehouse_name' => $request->warehouse_name,
            'warehouse_address' => $request->warehouse_address,
            'warehouse_telephone' => $request->warehouse_telephone,
            'is_active' => 1
        ]);

        return redirect()->route('warehouse.list')->with('success', 'Gudang berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $warehouse = Warehouse::find($id);
        if ($warehouse) {
            $warehouse->delete();
            return redirect()->route('warehouse.list')->with('success', 'Gudang berhasil dihapus!');
        } else {
            return redirect()->route('warehouse.list')->with('error', 'Gudang tidak ditemukan!');
        }
    }
}
