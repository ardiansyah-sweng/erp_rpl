<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillOfMaterial;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    public function addBillOfMaterial(Request $request)
    {
        $validatedData = $request->validate([
            'bom_name'          => 'required|string|min:3|unique:bill_of_material,bom_name',
            'measurement_unit'  => 'required|string|max:20',
            'total_cost'        => 'required|numeric|min:0',
            'active'            => 'required|boolean',
        ]);

        // Generate bom_id dengan format BOM001, BOM002, dst.
        $lastBom = BillOfMaterial::orderBy('id', 'desc')->first();
        $nextId = $lastBom ? ((int)substr($lastBom->bom_id, -3) + 1) : 1;
        $validatedData['bom_id'] = 'BOM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        BillOfMaterial::addBillOfMaterial($validatedData);

        return redirect()->back()->with('success', 'Bill of Material berhasil ditambahkan!');
    }


    // Fungsi untuk menghapus Bill of Material berdasarkan id
    public function deleteBillOfMaterial($id)
    {
        $deleted = BillOfMaterial::deleteBom($id);

        if ($deleted) {
            return response()->json(['message' => 'Bill of Material deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }
    }
    public function getBillOfMaterial()
    {
        $data = BillOfMaterial::getBillOfMaterial();
        return response()->json($data);
    }


    public function getBomDetail($id)
    {
        $bom = DB::table('bill_of_material')->where('id', $id)->first();

        if (!$bom) {
            return abort(404, 'Bill of Material tidak ditemukan');
        }

        $details = DB::table('bom_detail')
            ->where('bom_id', $bom->bom_id)
            ->select('id', 'bom_id', 'sku', 'quantity', 'cost', 'created_at', 'updated_at')
            ->get();

        return response()->json([
            'id'               => $bom->id,
            'bom_id'           => $bom->bom_id,
            'bom_name'         => $bom->bom_name,
            'measurement_unit' => $bom->measurement_unit,
            'total_cost'       => $bom->total_cost,
            'active'           => $bom->active,
            'created_at'       => $bom->created_at,
            'updated_at'       => $bom->updated_at,
            'details'          => $details,
        ]);
    }
    public function searchBillOfMaterial($keyword = null)
    {
        $data = BillOfMaterial::SearchOfBillMaterial($keyword);
        return response()->json([
            'success' => true,
            'message' => 'Data Bill of Material berhasil ditemukan.',
            'data' => $data
        ], 200);
    }
    public function updateBillOfMaterial($id, Request $request)
    {
        $data = $request->all();

        $bom = BillOfMaterial::updateBillOfMaterial($id, $data);

        if (!$bom) {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }

        return response()->json([
            'message' => 'Bill of Material updated successfully.',
            'data' => $bom
        ]);
    }

    /**
     * Tampilkan form edit BOM berdasarkan id.
     */
    public function editBom(int $id)
    {
        $bom = BillOfMaterial::findForEdit($id);

        if (!$bom) {
            return redirect()->route('bom.list')->with('error', 'Bill of Material tidak ditemukan.');
        }

        $measurementUnits = DB::table('measurement_unit')->orderBy('unit_name')->get();

        return view('bom.edit', compact('bom', 'measurementUnits'));
    }

    /**
     * Proses update BOM dari form edit (method POST dengan _method PUT).
     */
    public function updateBomForm(Request $request, int $id)
    {
        $request->validate([
            'bom_name'         => 'required|string|min:3|unique:bill_of_material,bom_name,' . $id,
            'measurement_unit' => 'required|integer|exists:measurement_unit,id',
            'total_cost'       => 'required|numeric|min:0',
            'active'           => 'required|boolean',
        ]);

        $bom = BillOfMaterial::findForEdit($id);

        if (!$bom) {
            return redirect()->route('bom.list')->with('error', 'Bill of Material tidak ditemukan.');
        }

        $bom->update([
            'bom_name'         => $request->bom_name,
            'measurement_unit' => (int) $request->measurement_unit,
            'total_cost'       => $request->total_cost,
            'active'           => $request->active,
        ]);

        return redirect()->route('bom.list')->with('success', 'Bill of Material berhasil diperbarui!');
    }
}
