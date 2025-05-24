<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function exportPdf(){
        $warehouse = [
            [
                'id' => 1,
                'warehouse_name' => 'Warehouse A',
                'warehouse_address' => 'Location A',
                'warehouse_telephone' => '1234567890',
                'is_active' =>true,
                'created_at' => '2023-01-01',
                'updated_at' => '2023-01-02',
            ],
            [
                'id' => 2,
                'warehouse_name' => 'Warehouse B',
                'warehouse_address' => 'Location B',
                'warehouse_telephone' => '1234567890',
                'is_active' =>false,
                'created_at' => '2023-02-01',
                'updated_at' => '2023-04-01',
            ],
            [
                'id' => 3,
                'warehouse_name' => 'Warehouse C',
                'warehouse_address' => 'Location C',
                'warehouse_telephone' => '1234567890',
                'is_active' =>true,
                'created_at' => '2023-01-06',
                'updated_at' => '2023-01-04',
            ],
        ];

        $pdf = Pdf::loadView('warehouse.report',compact('warehouse'));
        return $pdf->stream('warehouse_report.pdf');


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
}