<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPic;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class SupplierPicController extends Controller
{
    public function create()
{
    $suppliers = Supplier::all(['supplier_id', 'company_name']);
    return view('supplier.pic.add', compact('suppliers'));
}

    public function store(Request $request)
    {
        
        $request->validate([
            'supplier_id' => 'required|string|max:10',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'assignment_date' => 'required|date',
            'pic_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('pic_photo')) {
            $avatarPath = $request->file('pic_photo')->store('avatars', 'public');
        }

        SupplierPic::create([
            'supplier_id' => $request->supplier_id,
            'name' => $request->pic_name,
            'phone_number' => $request->telephone,
            'email' => $request->email,
            'assigned_date' => $request->assignment_date,
            'active' => $request->status ? 1 : 0,
            'avatar' => $avatarPath ? asset('storage/' . $avatarPath) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('supplier.pic.add')->with('success', 'Data PIC berhasil ditambahkan.');
    }
}
