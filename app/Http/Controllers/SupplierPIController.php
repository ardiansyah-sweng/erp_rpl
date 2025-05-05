<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SupplierPic;

class SupplierPIController extends Controller
{
    public function updateSupplierPICDetail(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:supplier_pics,email,' . $id,
            'password' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

            // Panggil fungsi dari model
            $result = SupplierPic::updateSupplierPIC($id, $request->all());

        return response()->json($result, $result['code']);
    }
}
