<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;
use App\Models\SupplierPICModel;
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
        $pic->supplier_name = $supplier ? $supplier->name : null;
        return view('supplier.pic.detail', ['pic' => $pic, 'supplier' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        // method update disini untuk update
    }

    public function searchSupplierPic(Request $request)
    {
        $keywords = $request->input('keywords');
        $supplierPics = SupplierPICModel::searchSupplierPic($keywords);

        return view('supplier.pic.list', ['pics' => $supplierPics, 'supplier_id' => $keywords]);
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

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('public/foto_pic');
            $validatedData['photo'] = basename($path);
        }

        $validatedData['assigned_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['assigned_date'])->format('Y-m-d');
        $validatedData['supplier_id'] = $supplierID;
        $validatedData['supplier_name'] = $request->input('supplier_name');

        SupplierPic::addSupplierPIC($supplierID, $validatedData);

        return redirect()->back()->with('success', 'PIC berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $pic = SupplierPic::findOrFail($id);
        $pic->delete();

        return redirect()->route('supplier.pic.list')->with('success', 'Data PIC berhasil dihapus.');
    }
}
