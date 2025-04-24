<?php

namespace App\Http\Controllers;

use App\Models\SupplierPic;

class PicSupplierController extends Controller
{
    public function index()
    {
        $pics = SupplierPic::with('supplier')->get();

        return view('supplier.pic.list', compact('pics'));
    }
}