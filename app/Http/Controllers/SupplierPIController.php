<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;
use Illuminate\Support\Facades\Validator;

class SupplierPIController extends Controller
{
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id); // memanggil method getPICByID dari model SupplierPic

        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $supplier = $pic->supplier;
        $pic->supplier_name = $supplier ? $supplier->name : null;
        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

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
    }
    public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone_number' => 'required|numeric|digits_between:10,13',
                'assigned_date' => 'required|date',
            ]);

            $data = $request->only(['name', 'email', 'phone_number', 'assigned_date']);
             $result = SupplierPic::updateSupplierPIC($id, $data);

                 return response()->json([
                    'status' => $result['status'],
                    'message' => $result['message'],
                    'data' => $result['data'] ?? null
                    ], $result['code']);
        }
}
