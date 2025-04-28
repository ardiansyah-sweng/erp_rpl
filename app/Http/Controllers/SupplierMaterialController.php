<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierMaterial;

class SupplierMaterialController extends Controller
{
    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material', ['materials' => $materials]);
    }

     // Validasi data supplier material
     public function addSupplierMaterial(Request $request)
     {
         $validated = $request->validate([
             'product_id'        => 'required|string|size:4',
             'sku'               => 'required|string|max:50',
             'item_name'         => 'required|string|max:50',
             'measurement_unit'  => 'required|string|max:10',
             'avg_base_price'    => 'required|numeric|min:0',
             'selling_price'     => 'required|numeric|min:0',
             'purchase_unit'     => 'required|integer|between:0,255',
             'sell_unit'         => 'required|integer|between:0,255',
             'stock_unit'        => 'required|integer|between:0,255',
         ]);
 
         return redirect()->back()->with('success', 'Data supplier product berhasil divalidasi!');
     }
}