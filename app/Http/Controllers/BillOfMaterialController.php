<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillOfMaterial;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = BillOfMaterial::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bom_id', 'LIKE', "%{$search}%")
                  ->orWhere('bom_name', 'LIKE', "%{$search}%")
                  ->orWhere('measurement_unit', 'LIKE', "%{$search}%");
            });
        }
        
        // Paginate results
        $billOfMaterials = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('bom.list', compact('billOfMaterials'));
    }

    public function addBillOfMaterial(Request $request)
    {
        $validatedData = $request->validate([
            'bom_id'            => 'required|string|unique:bill_of_material,bom_id',
            'bom_name'          => 'required|string|min:3',
            'measurement_unit_id' => 'required',
            'total_cost'        => 'required|numeric|min:0',
            'active'            => 'required|boolean',
        ]);

        // Convert measurement_unit_id to measurement_unit
        $validatedData['measurement_unit'] = $validatedData['measurement_unit_id'];
        unset($validatedData['measurement_unit_id']);

        try {
            BillOfMaterial::create($validatedData);
            return redirect()->back()->with('success', 'Bill of Material berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan Bill of Material: ' . $e->getMessage());
        }
    }


    // Fungsi untuk menghapus Bill of Material berdasarkan id
    public function deleteBillOfMaterial($id)
    {
        try {
            $bom = BillOfMaterial::find($id);
            
            if (!$bom) {
                return redirect()->back()->with('error', 'Bill of Material tidak ditemukan.');
            }
            
            $bom->delete();
            
            return redirect()->back()->with('success', 'Bill of Material berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus Bill of Material: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $bom = BillOfMaterial::find($id);
        
        if (!$bom) {
            return redirect()->route('bom.list')->with('error', 'Bill of Material tidak ditemukan.');
        }
        
        return view('bom.edit', compact('bom'));
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
}
