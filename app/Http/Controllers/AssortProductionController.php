<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssortProductionController extends Controller
{
    public function getProduction()
    {
        // Mengambil data dari tabel 'assortment_production' langsung dari query builder
        $production = DB::table('assortment_production')->get();

        // Kembalikan data dalam format JSON
        return response()->json($production);
    }
}
