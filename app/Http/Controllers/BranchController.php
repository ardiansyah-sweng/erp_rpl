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
use App\Constants\Messages;

class BranchController extends Controller
{
    /** Display a listing of the resource. */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        // Use enhanced query for API requests
        if ($isApiRequest) {
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
        $branch = Branch::find($id);
        if (!$branch) {
            return abort(404, Messages::BRANCH_NOT_FOUND);
        }
        return view('branches.edit', compact('branch'));
    }

    public function store(StoreBranchRequest $request)
    {
        try {
            // Single business logic - no duplication!
            $branch = Branch::addBranch([
                BranchColumns::NAME => $request->input('branch_name'),
                BranchColumns::ADDRESS => $request->input('branch_address'),
                BranchColumns::PHONE => $request->input('branch_telephone'),
                BranchColumns::IS_ACTIVE => $request->boolean('is_active'),
            ]);

            // Handle API Response
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::BRANCH_CREATED,
                    'data' => new BranchResource($branch)
                ], 201);
            }

            // Handle Web Response
            return redirect()->route('branches.index')->with('success', Messages::BRANCH_CREATED);
            
        } catch (\Exception $e) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $branch = Branch::find($id);
        
        if (!$branch) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::BRANCH_NOT_FOUND
                ], 404);
            }
            return abort(404, Messages::BRANCH_NOT_FOUND);
        }

        // Handle API Request
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        if ($isApiRequest) {
            return response()->json([
                'success' => true,
                'data' => new BranchResource($branch)
            ]);
        }
        
        // Handle Web Request
        return view('branches.detail', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, $id)
    {
        try {
            $branch = Branch::find($id);
            
            if (!$branch) {
                $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
                
                if ($isApiRequest) {
                    return response()->json([
                        'success' => false,
                        'message' => Messages::BRANCH_NOT_FOUND,
                    ], 404);
                }
                return redirect()->back()->withInput()->with('error', Messages::BRANCH_NOT_FOUND);
            }

            $branch->update([
                BranchColumns::NAME => $request->input('branch_name'),
                BranchColumns::ADDRESS => $request->input('branch_address'),
                BranchColumns::PHONE => $request->input('branch_telephone'),
                BranchColumns::IS_ACTIVE => $request->boolean('is_active'),
            ]);

            // Handle API Response
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::BRANCH_UPDATED,
                    'data' => new BranchResource($branch->fresh())
                ]);
            }
            // Handle Web Response
            return redirect()->route('branches.index')->with('success', Messages::BRANCH_UPDATED);

        } catch (\Exception $e) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        $branch = Branch::find($id);
        
        if (!$branch) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found'
                ], 404);
            }
            return redirect()->route('branches.index')->with('error', 'Branch not found');
        }

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
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::BRANCH_IN_USE
                ], 422);
            }
            return redirect()->route('branches.index')->with('error', Messages::BRANCH_IN_USE);
        }

        $deleted = $branch->delete();

        if ($deleted) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::BRANCH_DELETED
                ]);
            }
            return redirect()->route('branches.index')->with('success', Messages::BRANCH_DELETED);
        }

        // Gagal hapus branch
        if ($isApiRequest) {
            return response()->json([
                'success' => false,
                'message' => Messages::BRANCH_DELETE_FAILED
            ], 422);
        }
        return redirect()->route('branches.index')->with('error', Messages::BRANCH_DELETE_FAILED);
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
     * DEPRECATED: Keep for backward compatibility - will be removed
     */
    public function getBranchById($id)
    {
        return $this->show(request(), $id);
    }

    // Helper method for web routes compatibility
    public function getBranchAll(Request $request)
    {
        return $this->index($request);
    }
}
