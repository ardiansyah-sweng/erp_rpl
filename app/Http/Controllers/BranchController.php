<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchById($id)
    {
        return (new Branch)->getBranchById($id);
    }

    public function getBranchAll()
    {
        $branches = Branch::getAllBranch();
        return view('branch.list', ['branches' => $branches]);
    }

    public function addBranch(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|min:3|unique:branches,branch_name',
            'branch_address' => 'required|string|min:3',
            'branch_telephone' => 'required|string|min:3'
        ]);

        $branch = new Branch();
        $branch->addBranch([
            'branch_name' => $request->branch_name,
            'branch_address' => $request->branch_address,
            'branch_telephone' => $request->branch_telephone,
            'branch_status' => 1
        ]);

        return redirect()->route('branch.list')->with('success', 'Cabang berhasil ditambahkan!');
    }
}
