<?php

namespace App\Http\Controllers;

use App\Models\AssortmentProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class AssortProductionController extends Controller
{
    public function getProduction()
    {
        $model = new AssortmentProduction();
        $production = AssortmentProduction::paginate();

        return view('assortment_production.list', compact('production'));
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

    public function deleteProduction($id)
    {
        // Cari production berdasarkan ID untuk mendapatkan production_number
        $production = AssortmentProduction::find($id);
        if (!$production) {
            return response()->json(['message' => 'Data dengan ID tersebut tidak ditemukan'], 404);
        }

        // Panggil method deleteProduction yang sudah ada di Model
        // Method ini menggunakan production_number sebagai parameter
        $result = AssortmentProduction::deleteProduction($production->production_number);

        // Return response dari Model (pastikan method di model return boolean)
        if ($result) {
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['message' => 'Gagal menghapus data'], 500);
        }
    }

    public function addProduction(Request $request)
    {
        $data = [
            'in_production'    => 0,
            'production_number' => $request->production_number,
            'sku'              => $request->sku,
            'branch_id'        => $request->branch_id,
            'rm_whouse_id'     => $request->rm_whouse_id,
            'fg_whouse_id'     => $request->fg_whouse_id,
            'production_date'  => $request->production_date,
            'finished_date'    => $request->finished_date,
            'description'      => $request->description,
        ];

        $production = AssortmentProduction::addProduction($data);

        return response()->json([
            'message' => 'Production record added successfully.',
            'data' => $production
        ]);
    }
}
