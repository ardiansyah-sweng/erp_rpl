<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPIController extends Controller
{
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id);
        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $supplier = $pic->supplier;
        $pic->supplier_name = $supplier ? $supplier->name : null;
        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        // method update di sini
    }
    public function countPICModel($supplier_id){
        //VALIDASI
        if(!$supplier_id){
            return respounse()->json([
                'sukses' => false,
                'pesan' => 'Supplier ID tidak valid atau tidak diisi'
            ],400);
        }
        //MENGHITUNG JUMLAH PIC berdasarkan pada method countSupplierPIC di Model SupplierPic
        $count = SupplierPic::countSupplierPIC($supplier_id);
        if(!$count){
            return response()->json([
                'sukses' => false,
                'pesan' => 'Tidak ada PIC'
            ], 404);
        }
        return response()->json([
            'sukses' => true,
            'supplier_id' => $supplier_id,
            'pesan' => 'Jumlah PIC untuk supplier ini',
            'pic_count' => $count->jumlahnya
        ]);
    }
}
