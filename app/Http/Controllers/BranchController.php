<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    /**
     * Menambahkan branch baru dengan validasi
     */
    public function addBranch(Request $request)
    {
        // Ambil data dari request
        $data = $request->only(['branch_name', 'branch_address', 'branch_telephone', 'branch_status']);

        // Panggil fungsi addBranch dari model
        $result = Branch::addBranch($data);

        // Jika ada error validasi, kembalikan response error
        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 400);
        }

        return response()->json([
            'message' => 'Branch berhasil ditambahkan!',
            'branch'  => $result,
        ], 201);
    }

    /**
     * Mendapatkan data branch berdasarkan ID
     */
    public function getBranchById($id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch tidak ditemukan'], 404);
        }

        return response()->json($branch);
    }
}
