<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillOfMaterial;
use Illuminate\Support\Facades\DB;


class BillOfMaterialController extends Controller
{
    /**
     * Tampilkan halaman daftar Bill of Material dengan search dan pagination.
     */
    public function listBOMs(Request $request)
    {
        $search = $request->input('search');
        $boms   = BillOfMaterial::SearchOfBillMaterial($search);
        $measurement_units = \App\Models\MeasurementUnit::all();

        return view('bom.list', compact('boms', 'search', 'measurement_units'));
    }

    /**
     * Tambah Bill of Material baru.
     */
    public function addBillOfMaterial(Request $request)
    {
        $validatedData = $request->validate([
            'bom_name'         => 'required|string|min:3|unique:bill_of_material,bom_name',
            'measurement_unit' => 'required|integer|min:1',
            'total_cost'       => 'required|numeric|min:0',
            'active'           => 'required|boolean',
        ]);

        // Generate bom_id dengan format BOM-001, BOM-002, dst.
        $lastBom = BillOfMaterial::orderBy('id', 'desc')->first();
        $nextId  = $lastBom ? ((int) substr($lastBom->bom_id, -3) + 1) : 1;
        $validatedData['bom_id'] = 'BOM-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        BillOfMaterial::addBillOfMaterial($validatedData);

        return redirect()->route('bom.list')->with('success', 'Bill of Material berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit Bill of Material berdasarkan ID.
     */
    public function edit($id)
    {
        $bom = BillOfMaterial::getBomForEdit($id);

        if (!$bom) {
            abort(404, 'Bill of Material tidak ditemukan.');
        }

        // Ambil detail item dari bom_detail
        $details = DB::table('bom_detail')
            ->where('bom_id', $bom->bom_id)
            ->select('id', 'bom_id', 'sku', 'quantity', 'cost')
            ->get();

        $measurement_units = \App\Models\MeasurementUnit::all();

        return view('bom.edit', compact('bom', 'details', 'measurement_units'));
    }

    /**
     * Proses update data Bill of Material (Web form submission).
     */
    public function updateBillOfMaterial(Request $request, $id)
    {
        $bom = BillOfMaterial::getBomForEdit($id);

        if (!$bom) {
            abort(404, 'Bill of Material tidak ditemukan.');
        }

        // Validasi input dari form
        $validatedData = $request->validate([
            'bom_name'         => 'required|string|min:3',
            'measurement_unit' => 'required|integer|min:1',
            'total_cost'       => 'required|numeric|min:0',
            'active'           => 'required|boolean',
        ]);

        $bom->update($validatedData);

        return redirect()->route('bom.list')->with('success', 'Bill of Material berhasil diperbarui!');
    }

    /**
     * Hapus Bill of Material berdasarkan id.
     */
    public function deleteBillOfMaterial($id)
    {
        $bom = BillOfMaterial::find($id);

        if (!$bom) {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }

        $bom->delete();
        return response()->json(['message' => 'Bill of Material deleted successfully.'], 200);
    }

    /**
     * Kembalikan daftar BOM dalam format JSON (API).
     */
    public function getBillOfMaterial()
    {
        $data = BillOfMaterial::getBillOfMaterial();
        return response()->json($data);
    }

    /**
     * Kembalikan detail BOM beserta item-nya dalam format JSON (API).
     */
    public function getBomDetail($id)
    {
        $bom = DB::table('bill_of_material')
            ->leftJoin('measurement_unit', 'bill_of_material.measurement_unit', '=', 'measurement_unit.id')
            ->where('bill_of_material.id', $id)
            ->select('bill_of_material.*', 'measurement_unit.unit_name')
            ->first();

        if (!$bom) {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }

        // Ambil detail dari tabel bom_detail
        $details = DB::table('bom_detail')
            ->where('bom_id', $bom->bom_id)
            ->select('id', 'bom_id', 'sku', 'quantity', 'cost', 'created_at', 'updated_at')
            ->get();

        return response()->json([
            'id'               => $bom->id,
            'bom_id'           => $bom->bom_id,
            'bom_name'         => $bom->bom_name,
            'measurement_unit' => $bom->unit_name ?? $bom->measurement_unit,
            'total_cost'       => $bom->total_cost,
            'active'           => $bom->active,
            'created_at'       => $bom->created_at,
            'updated_at'       => $bom->updated_at,
            'details'          => $details,
        ]);
    }

    /**
     * Cari BOM berdasarkan keyword (API).
     */
    public function searchBillOfMaterial($keyword = null)
    {
        $data = BillOfMaterial::SearchOfBillMaterial($keyword);
        return response()->json([
            'success' => true,
            'message' => 'Data Bill of Material berhasil ditemukan.',
            'data'    => $data,
        ], 200);
    }

}
