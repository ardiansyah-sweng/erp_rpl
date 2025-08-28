<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\PurchaseOrder;
use App\Http\Requests\StoreBranchRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Constants\BranchColumns;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $branches = Branch::getAllBranch($search);

        if ($request->has('export') && $request->input('export') ==='pdf'){
            $pdf = Pdf::loadView('branch.report', ['branches' => $branches]);
            return $pdf->stream('report-branch.pdf');
        }
        
        return view('branches.index', ['branches' => $branches]);
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(StoreBranchRequest $request)
    {
        Branch::addBranch([
            BranchColumns::NAME => $request->branch_name,
            BranchColumns::ADDRESS => $request->branch_address,
            BranchColumns::PHONE => $request->branch_telephone,
            BranchColumns::IS_ACTIVE => 1,
        ]);

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambahkan!');
    }

    public function getBranchById($id)
    {
        $branch = (new Branch())->getBranchByID($id);

        if (!$branch) {
            return abort(404, 'Cabang tidak ditemukan');
        }

        return view('branch.detail', compact('branch'));
    }

    public function updateBranch(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'branch_name' => 'required|string|min:3',
            'branch_address' => 'required|string|min:3',
            'branch_telephone' => 'required|string|min:3',
        ]);
    
        // Panggil model untuk update data
        $branch = new Branch();
        $branch->updateBranch($id, [
            'branch_name' => $request->branch_name,
            'branch_address' => $request->branch_address,
            'branch_telephone' => $request->branch_telephone,
        ]);
    
        // Redirect kembali ke list dengan pesan sukses
        return redirect()->route('branch.list')->with('success', 'Cabang berhasil diupdate!');
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
