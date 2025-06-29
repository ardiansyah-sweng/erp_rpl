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
 public function getWarehouseAll()
    {
        // Memanggil metode static getWarehouseAll() dari Warehouse Model
        $warehouses = Warehouse::getWarehouseAll(); // Ini adalah baris pentingnya

        // Mengembalikan data sebagai JSON
        return response()->json($warehouses);
    }

}