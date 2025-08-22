<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function getMerkById($id)
    {
        $merk = (new Merk())->getMerkByID($id);

        if (!$merk)
        {
            return abort(404, 'Merk tidak ditemukan');
        }

        return view('merk.detail', compact('merk'));

    }

    public function updateMerk(Request $request, $id)
    {
       // Validasi input
       $request->validate([
        'id' => 'required|integer',
        'merk' => 'required|string|max:100',
        ]);

           // Update data merk
        $updatedMerk = Merk::updateMerk($request->id, $request->only(['merk']));

        if (!$updatedMerk)
        {
            return response()->json(['message' => 'Data Merk Tidak Tersedia'], 404);
        }

        return response()->json([ 'message' => 'Data Merk berhasil diperbarui','data' => $updatedMerk, ]);
    }

    public function getMerkAll()
    {
        $merks = Merk::getAllMerk();
        return view('merk.list', compact('merks'));
    }

     public function deleteMerk($id)
    {
        $deleted = Merk::deleteMerk($id);

        if ($deleted) {
        return redirect()->back()->with('success', 'Merk berhasil dihapus.');
        }
        else {
        return redirect()->back()->with('error', 'Merk gagal dihapus.');
        }
    }
    public function addMerk(Request $request)
    {
        // Validasi input
        $request->validate([
            'merk' => 'required|string|max:100',
            'active' => 'nullable|boolean',
        ]);

    $namaMerk = $request->input('merk');
    $active = $request->input('active', 1); // default aktif

    $newMerk = Merk::addMerk($namaMerk, $active);

    return redirect()->route('merk.list')->with('success', 'Merk berhasil ditambahkan');
    }
}