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
    public function getProductionDetail($id)
    {
        $details = DB::table('assortment_production_detail')
            ->join('assortment_production', 'assortment_production_detail.production_number', '=', 'assortment_production.production_number')
            ->select('assortment_production_detail.*', 'assortment_production.*')
            ->where('assortment_production.id', $id)
            ->first();

        return response()->json($details);
    }
}
