<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;
use Carbon\Carbon;

class SupplierPicController extends Controller
{
    public function getSupplierPicById($supplier_id)
    {
        $supplierPic = (new SupplierPic())->getSupplierPicById($supplier_id);

        if (!$supplierPic) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $assignedDate = Carbon::parse($supplierPic->assigned_date)->startOfDay();
        $now = Carbon::now()->startOfDay();
        $lamaAssigned = $assignedDate->diffInDays($now);

        return response()->json([
            'data' => $supplierPic,
            'lama_assigned' => $lamaAssigned
        ]);
    }
}
