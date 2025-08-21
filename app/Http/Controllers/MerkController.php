<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // return response()->json(data: $merks);
        //ini saya ubah jadi view agar bisa berjalan di view merk/list
        return view('merk.list',compact('merks'));
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


    public function printMerkPDF()
    {
        $merks = Merk::all();
        $pdf = Pdf::loadView('merk.cetak', compact('merks'));
        return $pdf->stream('daftar_merk.pdf');
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

        return response()->json([
            'message' => 'Merk berhasil ditambahkan',
            'data' => $newMerk,
        ]);

    }
}

