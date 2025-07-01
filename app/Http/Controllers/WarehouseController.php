<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function countWarehouse()
    {
        $total = Warehouse::countWarehouse();

        return response()->json([
            'total_warehouse' => $total
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

}