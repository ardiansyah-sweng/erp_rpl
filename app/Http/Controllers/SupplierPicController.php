<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPicController extends Controller
{
    public function getSupplierPICAll(Request $request)
    {
        $search = $request->input('search');
        $supplier_PIC = SupplierPic::getSupplierPICAll($search);
        return view('supplierPic.list', ['supplier_PIC' => $supplier_PIC]);
    }
}
