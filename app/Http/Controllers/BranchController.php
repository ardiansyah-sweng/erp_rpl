<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\BranchCollection;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Constants\BranchColumns;

class BranchController extends Controller
{
    /** Display a listing of the resource. */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Use enhanced query for API requests
        if ($this->wantsJson($request)) {
            // Best Practice: Use Model method instead of Controller query
            $filters = [
                'search' => $search,
                'status' => $request->get('status'),
                'sort_by' => $request->get('sort_by', BranchColumns::CREATED_AT),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            $query = Branch::searchWithFilters($filters);
            $branches = $query->paginate($request->get('per_page', 15));
            return new BranchCollection($branches);
        }

        // Web functionality - use existing logic
        $branches = Branch::getAllBranch($search);

        // Handle PDF Export (existing functionality)
        if ($request->has('export') && $request->input('export') === 'pdf'){
            $pdf = Pdf::loadView('branch.report', ['branches' => $branches]);
            return $pdf->stream('report-branch.pdf');
        }
        
        return view('branches.index', ['branches' => $branches]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit($id)
    {
        $branch = \App\Models\Branch::getBranchById($id);
        if (!$branch) {
            return abort(404, 'Cabang tidak ditemukan');
        }
        return view('branches.edit', compact('branch'));
    }

    public function store(StoreBranchRequest $request)
    {
        try {
            // Single business logic - no duplication!
            $branch = Branch::addBranch([
                BranchColumns::NAME => $request->input('branch_name') ?? $request->input(BranchColumns::NAME),
                BranchColumns::ADDRESS => $request->input('branch_address') ?? $request->input(BranchColumns::ADDRESS),
                BranchColumns::PHONE => $request->input('branch_telephone') ?? $request->input(BranchColumns::PHONE),
                BranchColumns::IS_ACTIVE => $request->input(BranchColumns::IS_ACTIVE, 0),
            ]);

            // Handle API Response
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Branch created successfully',
                    'data' => new BranchResource($branch)
                ], 201);
            }

            // Handle Web Response (existing)
            return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $branch = Branch::getBranchById($id);
        if (!$branch) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang tidak ditemukan!'
                ], 404);
            }
            return abort(404, 'Cabang tidak ditemukan');
        }
        // Handle API Request
        if ($this->wantsJson($request)) {
            return new BranchResource($branch);
        }
        // Handle Web Request (existing)
        return view('branches.detail', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, $id)
    {
        try {
            // Single business logic
            $updated = Branch::updateBranch($id, [
                BranchColumns::NAME => $request->input('branch_name') ?? $request->input(BranchColumns::NAME),
                BranchColumns::ADDRESS => $request->input('branch_address') ?? $request->input(BranchColumns::ADDRESS),
                BranchColumns::PHONE => $request->input('branch_telephone') ?? $request->input(BranchColumns::PHONE),
                BranchColumns::IS_ACTIVE => $request->input('is_active', 0),
            ]);

            if ($updated) {
                $branch = Branch::getBranchById($id);

                // Handle API Response
                if ($this->wantsJson($request)) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Branch updated successfully',
                        'data' => new BranchResource($branch)
                    ]);
                }

                // Handle Web Response (existing)
                return redirect()->route('branches.index')->with('success', 'Cabang berhasil diupdate!');
            }

            throw new \Exception('Failed to update branch');

        } catch (\Exception $e) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $branch = Branch::getBranchById($id);

        // Validasi relasi dengan try-catch agar tidak error jika tabel belum ada
        $purchaseOrderExists = false;
        $assortmentExists = false;
        try {
            $purchaseOrderExists = \DB::table('purchase_order')->where('branch_id', $id)->exists();
        } catch (\Exception $e) {
            $purchaseOrderExists = false;
        }
        try {
            $assortmentExists = \DB::table('assortment_production')->where('branch_id', $id)->exists();
        } catch (\Exception $e) {
            $assortmentExists = false;
        }
        if ($purchaseOrderExists || $assortmentExists) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang tidak bisa dihapus karena masih digunakan di tabel lain!'
                ], 422);
            }
            return redirect()->route('branches.index')->with('error', 'Cabang tidak bisa dihapus karena masih digunakan di tabel lain!');
        }

        $deleted = Branch::deleteBranch($id);

        if ($deleted) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cabang berhasil dihapus!'
                ]);
            }
            return redirect()->route('branches.index')->with('success', 'Cabang berhasil dihapus!');
        }

        // Gagal hapus branch (branch tidak ditemukan atau error lain)
        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cabang!'
            ], 422);
        }
        return redirect()->route('branches.index')->with('error', 'Gagal menghapus cabang!');
    }

    /**
     * API-specific endpoints (moved from BranchApiController)
     */
    public function active(Request $request)
    {
        // Best Practice: Use Model method
        if ($request->has('per_page')) {
            $branches = Branch::getActiveBranchesPaginated($request->get('per_page', 15));
        } else {
            $branches = Branch::where(BranchColumns::IS_ACTIVE, true)
                             ->orderBy(BranchColumns::CREATED_AT, 'desc')
                             ->get();
        }

        return new BranchCollection($branches);
    }

    public function statistics(Request $request)
    {
        // Best Practice: Use Model method
        $stats = Branch::getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function search(Request $request)
    {
        // Best Practice: Use Model method with filters
        $filters = [
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : null,
            'sort_by' => BranchColumns::CREATED_AT,
            'sort_order' => 'desc'
        ];

        $query = Branch::searchWithFilters($filters);
        $branches = $query->paginate($request->get('per_page', 15));

        return new BranchCollection($branches);
    }

    /**
     * Helper method to detect if request wants JSON response
     */
    private function wantsJson(Request $request): bool
    {
        return $request->expectsJson() || 
               $request->is('api/*') || 
               $request->header('Accept') === 'application/json' ||
               $request->header('Content-Type') === 'application/json';
    }

    /**
     * DEPRECATED: Keep for backward compatibility - will be removed
     */
    public function getBranchById($id)
    {
        return $this->show(request(), $id);
    }

    public function updateBranch(Request $request, $id)
    {
        // Validate manually since this bypasses UpdateBranchRequest
        $request->validate([
            'branch_name' => 'required|string|min:3',
            'branch_address' => 'required|string|min:3',
            'branch_telephone' => 'required|string|min:3',
        ]);

        return $this->update($request, $id);
    }

    public function deleteBranch($id)
    {
        return $this->destroy(request(), $id);
    }

    // Helper method for web routes compatibility
    public function getBranchAll(Request $request)
    {
        return $this->index($request);
    }
}
