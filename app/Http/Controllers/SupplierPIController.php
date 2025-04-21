<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierPIController extends Controller
{
    public function searchSupplierPic(Request $request)
    {
        $keywords = $request->input('keywords'); 
        $supplierPics = Supplier::searchByKeywords($keywords); 
        return view('supplier.pic_list', ['supplierPics' => $supplierPics]); 
    }
}

