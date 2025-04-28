<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function getUpdateSupplier($supplier_id)
    {
        $supplier = Supplier::getUpdateSupplier($supplier_id)->first();

        if (!$supplier) {
            return response()->json(['message' => 'Data Supplier Tidak Tersedia'], 404);
        }

        return response()->json($supplier);
    }
}