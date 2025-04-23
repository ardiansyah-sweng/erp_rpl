<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function getSupplierById($id)
    {
    $sup = (new Supplier())->getSupplierById($id);

    return response()->json($sup);
    }
}
