<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = [
            [
                'id' => 1,
                'nama_produk' => 'Kopi Hitam',
                'tanggal_produksi' => '2025-06-01',
                'jumlah' => 100,
                'status' => 'Selesai',
            ],
            [
                'id' => 2,
                'nama_produk' => 'Latte',
                'tanggal_produksi' => '2025-06-02',
                'jumlah' => 80,
                'status' => 'Proses',
            ],
            [
                'id' => 3,
                'nama_produk' => 'Cappuccino',
                'tanggal_produksi' => '2025-06-03',
                'jumlah' => 120,
                'status' => 'Selesai',
            ],
        ];

        return view('productions.index', compact('productions'));
    }
}
