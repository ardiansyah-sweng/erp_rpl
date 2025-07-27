<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;
use App\Models\SupplierPICModel;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
		



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

    public function searchSupplierPic(Request $request)
    {
        $keywords = $request->input('keywords');
        $supplierPics = SupplierPICModel::searchSupplierPic($keywords);

        return view('supplier.pic.list', ['pics' => $supplierPics, 'supplier_id' => $keywords]);
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

        // Cek duplikat menggunakan method model
        if (SupplierPic::isDuplicatePIC(
            $supplierID,
            $request->input('name'),
            $request->input('email'),
            $request->input('phone_number')
        )) {
            return redirect()->back()
                ->withErrors(['duplicate' => 'Data PIC dengan informasi yang sama sudah ada dan tidak bisa disimpan.'])
                ->withInput();
        }


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

    public function getSupplierPICAll()
    {
        $supplierPICs = SupplierPic::getSupplierPICAll(); // ini method dari model kamu
        return view('supplier.pic.list', ['pics' => $supplierPICs]);
    }
    
    public function deleteSupplierPIC($id)
    {
        $picDelete = SupplierPic::deleteSupplierPIC($id);

        if ($picDelete) {
            return redirect()->back()->with('success', 'PIC berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'PIC gagal dihapus.');
        }
    }

    public function updateSupplierPICDetail(Request $request, $id)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'supplier_id'   => 'required|string|exists:supplier,supplier_id',
            'name'          => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'email'         => 'required|email|unique:supplier_pic,email,' . $id,
            'assigned_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // 2. Ambil data hasil validasi
        $data = $request->only([
            'supplier_id',
            'name',
            'phone_number',
            'email',
            'assigned_date'
        ]);

        // 3. Panggil method dari MODEL: updateSupplierPIC($id)
        $result = SupplierPic::updateSupplierPIC($id, $data);

        // 4. Return response JSON
        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
            'data'    => $result['data'] ?? null,
        ], $result['code'] ?? 200);

    }

    public function cetakPdf()
    {
        $pics = SupplierPic::getSupplierPICAll();
        $pics->load('supplier'); 

        $data = [
            'pics' => $pics
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('supplier.pic.pdfpic', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('PIC-Supplier-Semua.pdf');
    }

}
