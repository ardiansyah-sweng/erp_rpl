<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function getWarehouseById($id)
    {
        $warehouse = (new Warehouse())->getWarehouseByID($id);

        if (!$warehouse) 
        {
            return abort(404, 'Warehouse tidak ditemukan');
        }

        return response()->json($warehouse);

    }

    public function deleteWarehouse($id)
    {
        $deleted = Warehouse::deleteWarehouseById($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Warehouse berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Warehouse tidak ditemukan atau gagal dihapus.');
        }
    }
}