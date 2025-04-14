<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function deleteSupplierByID($id)
    {
    if (Supplier::deleteSupplier($id)) {
        return redirect()->back()->with('success', 'Supplier deleted successfully');
        }
    return redirect()->back()->with('error', 'Supplier not found');
    }

}
