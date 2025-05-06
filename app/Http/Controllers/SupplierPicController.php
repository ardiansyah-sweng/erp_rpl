<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPicController extends Controller
{
    public function getSupplierPICAll()
    {
        $supplierPICs = SupplierPICModel::getSupplierPICAll(); 
        return view('supplier.pic.list', compact('supplierPICs'));
    }

}
