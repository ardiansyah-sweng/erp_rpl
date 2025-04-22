<?php

namespace App\Http\Controllers;

use App\Models\MerkModel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function generateCabangPDF()
    {

        $data = MerkModel::getMerkAll();        

        $pdf = PDF::loadView('laporan.cabang', ['data' => $data]);
        return $pdf->stream('laporan_daftar_cabang.pdf');

    }
}
