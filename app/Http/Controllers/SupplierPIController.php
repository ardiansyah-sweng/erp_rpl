<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;
use App\Models\SupplierPic;
use App\Models\SupplierPICModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;


use Carbon\Carbon;




=======
use App\Models\Supplier;
use App\Models\SupplierPic;
use App\Models\SupplierPICModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

class SupplierPIController extends Controller
{
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id); // memanggil method getPICByID dari model SupplierPic

<<<<<<< HEAD
        if (!$pic) {
=======
        if (! $pic) {
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $supplier = $pic->supplier;
<<<<<<< HEAD
        $pic->supplier_name = $supplier ? $supplier->name : null;
        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

=======
        $pic->supplier_name = $supplier ? $supplier->company_name : null;

        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

    public function edit($id)
    {
        $pic = SupplierPic::getPICByID($id);
        if (! $pic) {
            return redirect('/supplier/pic/list')->with('error', 'PIC tidak ditemukan.');
        }
        $supplier = $pic->supplier;
        $pic->supplier_name = $supplier ? $supplier->company_name : null;

        return view('supplier.pic.edit', ['pic' => $pic]);
    }

    public function updatePIC(Request $request, $id)
    {
        $pic = SupplierPic::getPICByID($id);
        if (! $pic) {
            return redirect('/supplier/pic/list')->with('error', 'PIC tidak ditemukan.');
        }

        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|email|max:50',
            'telephone' => 'required|string|max:30',
        ]);

        // Mapping to model attributes
        $data = [
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['telephone'],
        ];

        SupplierPic::updateSupplierPIC($id, $data);

        return redirect()->back()->with('success', 'PIC berhasil diperbarui!');
    }

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public function searchSupplierPic(Request $request)
    {
        $keywords = $request->input('keywords');
        $supplierPics = SupplierPICModel::searchSupplierPic($keywords);

        return view('supplier.pic.list', ['pics' => $supplierPics, 'supplier_id' => $keywords]);
    }

<<<<<<< HEAD

=======
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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

<<<<<<< HEAD

=======
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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
<<<<<<< HEAD
=======

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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
<<<<<<< HEAD
            'supplier_id'   => 'required|string|exists:supplier,supplier_id',
            'name'          => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'email'         => 'required|email|unique:supplier_pic,email,' . $id,
=======
            'supplier_id' => 'required|string|exists:supplier,supplier_id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:supplier_pic,email,'.$id,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            'assigned_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
<<<<<<< HEAD
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
=======
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            ], 422);
        }

        // 2. Ambil data hasil validasi
        $data = $request->only([
            'supplier_id',
            'name',
            'phone_number',
            'email',
<<<<<<< HEAD
            'assigned_date'
=======
            'assigned_date',
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        ]);

        // 3. Panggil method dari MODEL: updateSupplierPIC($id)
        $result = SupplierPic::updateSupplierPIC($id, $data);

        // 4. Return response JSON
        return response()->json([
<<<<<<< HEAD
            'status'  => $result['status'],
            'message' => $result['message'],
            'data'    => $result['data'] ?? null,
=======
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        ], $result['code'] ?? 200);
    }

    public function cetakPdf()
    {
        // ambil semua PIC beserta relasi supplier, tanpa limit
        $pics = SupplierPic::with('supplier')->get();

        $data = [
<<<<<<< HEAD
            'pics' => $pics
=======
            'pics' => $pics,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        ];

        $pdf = Pdf::loadView('supplier.pic.pdfpic', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('PIC-Supplier-Semua.pdf');
    }

<<<<<<< HEAD

=======
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public function getSupplierPicById($supplier_id)
    {
        $supplierPic = SupplierPic::where('supplier_id', $supplier_id)->first();

<<<<<<< HEAD
        if (!$supplierPic) {
=======
        if (! $supplierPic) {
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            return response()->json(['message' => 'Data not found'], 404);
        }

        $assignedDate = Carbon::parse($supplierPic->assigned_date)->startOfDay();
        $now = Carbon::now()->startOfDay();
        $lamaAssigned = $assignedDate->diffInDays($now);

        return response()->json([
            'data' => $supplierPic,
<<<<<<< HEAD
            'lama_assigned' => $lamaAssigned
        ]);
    }                            
=======
            'lama_assigned' => $lamaAssigned,
        ]);
    }
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

    public function getSupplierPIC($supplierID)
    {
        $pics = DB::table('supplier_pic')
            ->where('supplier_id', $supplierID)
            ->get()
            ->map(function ($pic) {
                $assignedDate = \Carbon\Carbon::parse($pic->assigned_date);
                $now = \Carbon\Carbon::now();
                $lamaAssigned = round($assignedDate->floatDiffInDays($now), 2);

                return (array) $pic + ['lama_assigned' => $lamaAssigned];
            });

        return response()->json([
            'message' => 'PIC list retrieved successfully.',
<<<<<<< HEAD
            'data' => $pics
=======
            'data' => $pics,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

        ]);
    }

    public function cetakPdfBySupplier($supplierID)
    {
        $supplier = Supplier::findOrFail($supplierID);
        $pics = SupplierPic::with('supplier')
<<<<<<< HEAD
                ->where('supplier_id', $supplierID)
                ->get();

        $pdf = PDF::loadView('supplier.pic.pdfPIC', [
            'pics' => $pics,
            'supplier' => $supplier
        ]);
        return $pdf->stream('PIC_Supplier_'.$supplier->supplier_id.'.pdf');
    }

=======
            ->where('supplier_id', $supplierID)
            ->get();

        $pdf = PDF::loadView('supplier.pic.pdfPIC', [
            'pics' => $pics,
            'supplier' => $supplier,
        ]);

        return $pdf->stream('PIC_Supplier_'.$supplier->supplier_id.'.pdf');
    }
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
}
