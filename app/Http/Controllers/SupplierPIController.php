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
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:supplier_pics,email,' . $id,
            'password'      => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $supplierPic = SupplierPic::find($id);

        if (!$supplierPic) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Supplier PIC tidak ditemukan.',
            ], 404);
        }

        $supplierPic->fill($request->all());

        if ($supplierPic->save()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Supplier PIC berhasil diperbarui.',
                'data'    => $supplierPic,
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui Supplier PIC.',
            ], 500);
        }
    }
}
