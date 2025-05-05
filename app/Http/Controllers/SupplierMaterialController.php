<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierMaterial;

class SupplierMaterialController extends Controller
{
    public function getSupplierMaterialByID($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier){
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }

    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material', ['materials' => $materials]);
    }
}
