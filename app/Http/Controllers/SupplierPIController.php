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
    public function addSupplierPIC(Request $request, $supplierID)
    {
        // Validasi input
        $validatedData = $request->validate([
            'supplier_id' => 'required|string|max:6',
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'phone_number' => 'required|string|max:30',
            'assigned_date' => 'required|date_format:d/m/Y',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        ]);

        // Handle upload foto jika ada
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('public/foto_pic'); // Disimpan di storage/app/public/foto_pic
            $validatedData['photo'] = basename($path); // hanya simpan nama file
        }

        // Format tanggal menjadi format Y-m-d (untuk MySQL)
        $validatedData['assigned_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['assigned_date'])->format('Y-m-d');

        // Tambahkan supplier_id dari parameter URL (bisa juga dari input langsung)
        $validatedData['supplier_id'] = $supplierID;

        // Tambahkan supplier_name meskipun tidak divalidasi
        $validatedData['supplier_name'] = $request->input('supplier_name');

        // Simpan ke database
        SupplierPic::addSupplierPIC($supplierID, $validatedData);

        return redirect()->back()->with('success', 'PIC berhasil ditambahkan!');
    }    
}
