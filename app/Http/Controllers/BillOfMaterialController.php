<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BillOfMaterial;

class BillOfMaterialController extends Controller
{
    // Fungsi untuk menambahkan Bill of Material
    public function addBillOfMaterial(Request $request)
    {
        $request->validate([
            'bom_name' => 'required|string|min:3|unique:bill_of_materials,bom_name',
            'measurement_unit' => 'required|string|max:20',
            'total_cost' => 'required|numeric|min:0',
            'active' => 'required|boolean'
        ]);

        BillOfMaterial::create([
            'bom_name' => $request->bom_name,
            'measurement_unit' => $request->measurement_unit,
            'total_cost' => $request->total_cost,
            'active' => $request->active,
        ]);

        return redirect()->route('billofmaterial.list')->with('success', 'Bill of Material berhasil ditambahkan!');
    }

    // Fungsi untuk menghapus Bill of Material berdasarkan id
    public function deleteBillOfMaterial($id)
    {
        $deleted = DB::table('bill_of_materials')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Bill of Material deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Bill of Material not found.'], 404);
        }
    }
}
