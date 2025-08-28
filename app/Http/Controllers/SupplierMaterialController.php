<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierMaterial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SupplierMaterialController extends Controller
{
    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material.list', ['materials' => $materials]);
    }

    public function getSupplierMaterialById($id)
    {
        $model = new SupplierMaterial();
        $material = $model->getSupplierMaterialById($id);

        if (!$material) {
            abort(404, 'Supplier material not found');
        }

        return view('supplier.material.detail', ['material' => $material]);
    }

     // Validasi data supplier material
     public function addSupplierMaterial(Request $request)
     {
        $validated = $request->validate([
            'supplier_id'   => 'required|string|size:6',
            'company_name'  => 'required|string|max:255', 
            'product_id'    => 'required|string|max:50',
            'product_name'  => 'required|string|max:255',
            'base_price'    => 'required|integer|min:0',
            'created_at'    => 'nullable|date',
            'updated_at'    => 'nullable|date',
        ]);
        SupplierMaterial::addSupplierMaterial((object)$validated);
         return redirect()->back()->with('success', 'Data supplier product berhasil divalidasi!'); 
     }

    public function updateSupplierMaterial(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id'    => 'required|string|max:50',
            'product_name'  => 'required|string|max:255',
            'base_price'    => 'required|integer|min:0'
        ]);

        $validated['updated_at'] = now();
        
        $model = new SupplierMaterial();
        $result = $model->updateSupplierMaterial($id, $validated);

        if ($result) {
            return redirect()->back()->with('success', 'Data supplier material berhasil diperbarui!');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui data supplier material!');
    }

    #cetak pdf
    public function cetakPDF($supplier_id)
    {
        $materials = SupplierMaterial::where('supplier_id', $supplier_id)->get();

        if ($materials->isEmpty()) {
            return redirect()->back()->with('error', 'Data supplier tidak ditemukan.');
        }

        $supplierName = $materials->first()->company_name;

        $pdf = Pdf::loadView('supplier.material.pdf', compact('materials', 'supplierName', 'supplier_id'));
        return $pdf->stream('data_material_' . $supplier_id . '.pdf');
    }

        public function getSupplierMaterialByProductType($supplier_id, $product_type)
    {
        // Validasi hanya menerima product_type tertentu
        if (!in_array($product_type, ['HFG', 'FG', 'RM'])) {
            return response()->json(['error' => 'Invalid product type'], 400);
        }

        $results = DB::table('supplier_product')
            ->join('products', DB::raw("SUBSTRING_INDEX(supplier_product.product_id, '-', 1)"), '=', 'products.product_id')
            ->join('item', 'products.product_id', '=', 'item.product_id')
            ->where('supplier_product.supplier_id', $supplier_id)
            ->where('products.product_type', $product_type)
            ->select(
                'supplier_product.supplier_id',
                'supplier_product.company_name',
                'supplier_product.product_id',
                'products.product_name',
                'products.product_type',
                'supplier_product.base_price',
                'item.item_name',
                'item.measurement_unit',
                'item.stock_unit'
            )
            ->get();

        return response()->json($results);
    }


}
