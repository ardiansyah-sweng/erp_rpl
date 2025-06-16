<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\AssortmentProduction;

class AssortmentProductionController extends Controller
{
    public function show($id)
    {
        $production = AssortmentProduction::getProductionDetail($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssortProductionController extends Controller
{
    public function getProduction()
    {
        // Mengambil data dari tabel 'assortment_production' langsung dari query builder
        $production = DB::table('assortment_production')->get();

        // Kembalikan data dalam format JSON
>>>>>>> 4e5a9c7b00e2bab9707f02dfb0f8ebc8404ecbf8
        return response()->json($production);
    }
}
