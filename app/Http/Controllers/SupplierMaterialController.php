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

    public function supplierMaterialSearch($keyword)
    {
        $results = SupplierMaterial::supplierMaterialSearch($keyword);
        return $results;
    }
}

