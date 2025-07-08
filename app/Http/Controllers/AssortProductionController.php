<?php

namespace App\Http\Controllers;

use App\Models\AssortmentProduction;
use Illuminate\Http\Request;

class AssortProductionController extends Controller
{
    public function getProduction()
    {
        $model = new AssortmentProduction();
        $production = $model->getProduction();

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

    public function getProductionDetail($production_number)
    {
        $productionDetail = AssortmentProduction::getProductionDetail($production_number);
        $data = $productionDetail->getData();

        if (!$data) {
            abort(404, 'Production not found');
        }

        return view('assortment_production.detail', compact('data'));
    }

    public function searchProduction($keyword)
    {
        $productions = DB::table('assortment_production')
            ->where('sku', 'like', "%{$keyword}%")
            ->get(['id', 'sku']); // ambil hanya kolom yang diperlukan

        return response()->json($productions); // hasilnya array of object
    }
}
