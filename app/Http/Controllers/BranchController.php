<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function getBranchById($id)
    {
        return (new Branch)->getBranchById($id);
    }

    public function addBranch(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string|min:3',
            'branch_address' => 'required|string',
            'branch_telephone' => 'required|string|min:6',
            'branch_status' => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        // Menambahkan branch baru
        $branch = Branch::addBranch(
            $request->branch_name,
            $request->branch_address,
            $request->branch_telephone,
            $request->branch_status ?? 1
        );

        return response()->json([
            'message' => 'Branch berhasil ditambahkan!',
            'data' => $branch
        ], 201);
    }
}
