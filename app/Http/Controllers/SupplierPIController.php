<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierPIController extends Controller
{
    public function searchSupplierPic(Request $request)
    {
        $keywords = $request->input('keywords');
        $supplierPics = SupplierPICModel::searchSupplierPic($keywords);
        
        return view('supplier-pic.list', ['supplierPics' => $supplierPics]);
    }
}
