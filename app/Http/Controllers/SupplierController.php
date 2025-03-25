<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;


class SupplierController extends Controller
{
    public function deleteSupplierByID($id)
    {
        if (Supplier::deleteSupplier($id)) {
            return redirect()->back()->with('success!!', 'Supplier deleted successfully');
        }
        return redirect()->back()->with('error!!', 'Supplier not found');
    }
}
