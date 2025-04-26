<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function getMerkById($id)
    {
        $merk = (new Merk())->getMerkByID($id);

        if (!$merk) {
            return abort(404, 'Merk tidak ditemukan');
        }

        return view('merk.detail', compact('merk'));
    }

    public function deleteMerk($id)
    {
        $merkModel = new Merk();

        // Validasi: pastikan $id angka dan lebih dari 0
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $deleted = $merkModel->deleteMerk($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Merk berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Merk tidak ditemukan atau gagal dihapus.');
        }
    }
}
