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
    public function countSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $count = $model->countSupplierMaterial();

        return response()->json([
            
            'Item Type RM' => $count
        ]);
    }

}