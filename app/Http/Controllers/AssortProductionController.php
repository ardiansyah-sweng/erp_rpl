<?php

namespace App\Http\Controllers;

use App\Models\AssortmentProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class AssortProductionController extends Controller
{
    public function getProduction(Request $request)
    {
        $search = $request->input('search');
        
        $query = AssortmentProduction::query();
        
        if ($search) {
            $query->where('sku', 'like', "%{$search}%")
                ->orWhere('production_number', 'like', "%{$search}%");
        }
        
        $production = $query->paginate();
        $productionCount = AssortmentProduction::count();
        
        return view('assortment_production.list', compact('production', 'productionCount'));
    }
    public function exportProductionPdf()
    {
        $production = AssortmentProduction::all(); // atau ->get() sesuai kebutuhan, tanpa paginate

        $pdf = Pdf::loadView('assortment_production.pdf', compact('production'));

        return $pdf->stream('laporan_production.pdf');
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
        $productions = AssortmentProduction::where('sku', 'like', "%{$keyword}%")->paginate(10);
    
        if ($productions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada production yang ditemukan untuk SKU: ' . $keyword);
        }
        
        $productionCount = AssortmentProduction::count();
        return view('assortment_production.list', compact('productions', 'productionCount'));
    }

    public function deleteProduction($id)
    {
        // Cari production berdasarkan ID untuk mendapatkan production_number
        $production = AssortmentProduction::find($id);
        if (!$production) {
            return response()->json(['message' => 'Data dengan ID tersebut tidak ditemukan'], 404);
        }

        // Kembalikan response dari Model secara langsung
        return AssortmentProduction::deleteProduction($production->production_number);
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
