<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;

class SupplierPICController extends Controller
{
    public function getPICByID($id)
    {
        $pic = SupplierPic::getPICByID($id);

        if (!$pic) {
            return redirect('/supplier')->with('error', 'PIC tidak ditemukan.');
        }
        $pic->supplier_name = $pic->name;
        return view('supplier.pic.edit', ['pic' => $pic]);
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone_number' => 'required|numeric|digits_between:10,13',
        'assigned_date' => 'required|date',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $pic = SupplierPic::findOrFail($id);

    // simpan data
    $pic->supplier_id = $request->supplier_id;
    $pic->name = $request->name;
    $pic->email = $request->email;
    $pic->phone_number = $request->phone_number;
    $pic->assigned_date = $request->assigned_date;
    $pic->active = $request->has('active') ? 1 : 0;

    // simpan foto jika diupload
    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/avatar', $filename);
        $pic->avatar = 'avatar/' . $filename;
    }

    $pic->save();

    return redirect('/supplier')->with('success', 'Data PIC berhasil diperbarui.');
}

}
