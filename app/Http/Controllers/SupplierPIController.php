<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\SupplierPic;
use App\Models\SupplierPICModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SupplierPIController extends Controller
{

    public function getPICByID($id)
    {

        $pic = SupplierPic::getPICByID($id);

        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }

        $supplier = $pic->supplier;

        $pic->supplier_name = $supplier ? $supplier->company_name : null;

        return view('supplier.pic.detail', [
            'pic' => $pic,
            'supplier' => $supplier
        ]);
    }

    public function searchSupplierPic(Request $request)
    {

        $keywords = $request->input('keywords');

        $supplierPics = SupplierPICModel::searchSupplierPic($keywords);

        return view('supplier.pic.list', [
            'pics' => $supplierPics,
            'supplier_id' => $keywords
        ]);
    }

    public function addSupplierPIC(Request $request, $supplierID)
    {

        $validatedData = $request->validate([

            'supplier_id' => 'required|string|max:6',

            'name' => 'required|string|max:50',

            'email' => 'required|email|max:50',

            'phone_number' => 'required|string|max:30',

            'assigned_date' => 'required|date_format:d/m/Y',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        if (
            SupplierPic::isDuplicatePIC(
                $supplierID,
                $request->input('name'),
                $request->input('email'),
                $request->input('phone_number')
            )
        ) {

            return redirect()->back()
                ->withErrors([
                    'duplicate' => 'Data PIC dengan informasi yang sama sudah ada dan tidak bisa disimpan.'
                ])
                ->withInput();
        }

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $path = $file->store('public/foto_pic');

            $validatedData['photo'] = basename($path);
        }

        $validatedData['assigned_date'] =
            \Carbon\Carbon::createFromFormat(
                'd/m/Y',
                $validatedData['assigned_date']
            )->format('Y-m-d');

        $validatedData['supplier_id'] = $supplierID;

        $validatedData['supplier_name'] = $request->input('supplier_name');

        SupplierPic::addSupplierPIC($supplierID, $validatedData);

        return redirect()->back()->with('success', 'PIC berhasil ditambahkan!');
    }

    public function getSupplierPICAll()
    {

        $supplierPICs = SupplierPic::getSupplierPICAll();

        return view('supplier.pic.list', [
            'pics' => $supplierPICs
        ]);
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
    $validator = Validator::make($request->all(), [

        'supplier_id' => 'required|string|exists:supplier,supplier_id',

        'name' => 'required|string|max:255',

        'phone_number' => 'required|string|max:20',

        'email' => 'required|email|unique:supplier_pic,email,' . $id,

        'assigned_date' => 'required|date',

    ]);

    if ($validator->fails()) {

        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $pic = SupplierPic::findOrFail($id);

    $pic->supplier_id = $request->supplier_id;
    $pic->name = $request->name;
    $pic->phone_number = $request->phone_number;
    $pic->email = $request->email;
    $pic->assigned_date = $request->assigned_date;
    $pic->status = $request->has('status') ? 1 : 0;

    if ($request->hasFile('photo')) {

        $file = $request->file('photo');

        $path = $file->store('public/foto_pic');

        $pic->photo = basename($path);
    }

    $pic->save();

    return redirect('/supplier/pic/list')
        ->with('success', 'Data PIC berhasil diupdate');
}

    public function edit($id)
    {

        $pic = SupplierPic::findOrFail($id);

        return view('supplier.pic.edit', compact('pic'));
    }

    public function cetakPdf()
    {

        $pics = SupplierPic::with('supplier')->get();

        $data = [
            'pics' => $pics,
        ];

        $pdf = Pdf::loadView('supplier.pic.pdfpic', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('PIC-Supplier-Semua.pdf');
    }

    public function getSupplierPicById($supplier_id)
    {

        $supplierPic = SupplierPic::where('supplier_id', $supplier_id)->first();

        if (!$supplierPic) {

            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        $assignedDate = Carbon::parse($supplierPic->assigned_date)->startOfDay();

        $now = Carbon::now()->startOfDay();

        $lamaAssigned = $assignedDate->diffInDays($now);

        return response()->json([

            'data' => $supplierPic,

            'lama_assigned' => $lamaAssigned,

        ]);
    }

    public function getSupplierPIC($supplierID)
    {

        $pics = DB::table('supplier_pic')

            ->where('supplier_id', $supplierID)

            ->get()

            ->map(function ($pic) {

                $assignedDate = \Carbon\Carbon::parse($pic->assigned_date);

                $now = \Carbon\Carbon::now();

                $lamaAssigned = round(
                    $assignedDate->floatDiffInDays($now),
                    2
                );

                return (array) $pic + [
                    'lama_assigned' => $lamaAssigned
                ];
            });

        return response()->json([

            'message' => 'PIC list retrieved successfully.',

            'data' => $pics,

        ]);
    }

    public function cetakPdfBySupplier($supplierID)
    {

        $supplier = Supplier::findOrFail($supplierID);

        $pics = SupplierPic::with('supplier')
            ->where('supplier_id', $supplierID)
            ->get();

        $pdf = Pdf::loadView('supplier.pic.pdfPIC', [

            'pics' => $pics,

            'supplier' => $supplier,

        ]);

        return $pdf->stream('PIC-Supplier.pdf');
    }
}