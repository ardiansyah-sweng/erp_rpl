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

    public function updateProduction(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'in_production'      => 'required|boolean',
            'production_number'  => 'required|string|max:9',
            'sku'                => 'required|string|max:50',
            'branch_id'          => 'required|integer',
            'rm_whouse_id'       => 'required|integer',
            'fg_whouse_id'       => 'required|integer',
            'production_date'    => 'required|string|max:45',
            'finished_date'      => 'nullable|date',
            'description'        => 'nullable|string|max:45',
        ]);

        // Memastikan data ID ada 
        $exists = DB::table('assortment_production')->where('id', $id)->exists();

        if (!$exists) {
            return response()->json(['message' => 'Data dengan ID tersebut tidak ditemukan'], 404);
        }

        // Update data
        $updated = DB::table('assortment_production')
            ->where('id', $id)
            ->update($validatedData);

        if ($updated) {
            return response()->json(['message' => 'Data berhasil diperbarui'], 200);
        } else {
            return response()->json(['message' => 'Data tidak mengalami perubahan'], 200);
        }
    }
    
    public function getProductionDetail($id)
    {
        $header = \App\Models\AssortmentProduction::find($id);

        if (!$header) {
            return response()->json(['message' => 'Production not found'], 404);
        }

        $production_number = $header->production_number;
        return \App\Models\AssortmentProduction::getProductionDetail($production_number);
    }
}
