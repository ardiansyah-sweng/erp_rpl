<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;

class SupplierMaterialController extends Controller
{
    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();
        return view('supplier.material.list', ['materials' => $materials]);
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
    public function getSupplierMaterialByProductType($supplier_id, $product_type)
{
    if (!in_array($product_type, ['HFG', 'FG', 'RM'])) {
        return response()->json(['error' => 'Invalid product type'], 400);
    }

    $results = DB::table('supplier_product')
        ->join('products', 'supplier_product.product_id', '=', 'products.product_id')
        ->where('supplier_product.supplier_id', $supplier_id)
        ->where('products.product_type', $product_type)
        ->select(
            'supplier_product.supplier_id',
            'supplier_product.company_name',
            'supplier_product.product_id',
            'products.product_name',
            'products.product_type',
            'supplier_product.base_price'
        )
        ->get();

    return response()->json($results);
}
}
