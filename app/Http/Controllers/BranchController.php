<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchById($id)
    {
    $branch = (new Branch())->getBranchByID($id);

    if (!$branch) {
        return abort(404, 'Cabang tidak ditemukan');
    }

    return view('branch.detail', compact('branch'));
    }

    public function getBranchAll(Request $request)
    {
        $search = $request->input('search');
        $branches = Branch::getAllBranch($search);

        if ($request->has('export') && $request->input('export') ==='pdf'){
            $pdf = Pdf::loadView('branch.report', ['branches' => $branches]);
            return $pdf->stream('report-branch.pdf');
        }

        return view('branch.list', ['branches' => $branches]);
    }

    public function addBranch(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|min:3|unique:branch,branch_name',
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

    public function deleteBranch($id)
    {
        try {
            $branch = Branch::findBranch($id);

            if (PurchaseOrder::where('branch_id', $id)->exists()) {
                throw new \Exception('Cabang tidak bisa dihapus bila id branch sudah muncul di purchase_order!');
            }

            Branch::deleteBranch($id);
            return redirect()->route('branch.list')->with('success', 'Cabang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('branch.list')->with('error', $e->getMessage());
        }
    }
}
