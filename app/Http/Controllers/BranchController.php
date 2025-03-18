<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchById($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => (new Branch)->getBranchById($id)
        ]);
    }

    public function addBranch(Request $request)
    {
        // Validasi input
        $request->validate([
            'branch_name'      => 'required|min:3|unique:branch,branch_name',
            'branch_address'   => 'required|min:10',
            'branch_telephone' => 'required|min:10',
            'branch_status'    => 'required|boolean',
        ]);

        // Tambahkan data branch
        $branch = (new Branch)->addBranch($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Branch berhasil ditambahkan!',
            'data' => $branch
        ]);
    }

    public function updateBranch(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'branch_name'      => 'required|min:3|unique:branch,branch_name,' . $id,
            'branch_address'   => 'required|min:10',
            'branch_telephone' => 'required|min:10',
            'branch_status'    => 'required|boolean',
        ]);

        // Perbarui data branch
        $branch = (new Branch)->updateBranch($id, $request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Branch berhasil diperbarui!',
            'data' => $branch
        ]);
    }

    public function deleteBranch($id)
    {
        // Hapus branch berdasarkan ID
        (new Branch)->deleteBranch($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Branch berhasil dihapus!'
        ]);
    }
}
