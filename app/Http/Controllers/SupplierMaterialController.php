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

        dd($materials);

        return view('supplier.material', ['materials' => $materials]);
    }
}