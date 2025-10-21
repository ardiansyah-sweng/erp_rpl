<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerkRequest;
use App\Http\Requests\UpdateMerkRequest;
use App\Http\Resources\MerkCollection;
use App\Http\Resources\MerkResource;
use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Constants\Messages;
use App\Constants\MerkColumns;

/**
 * MerkController
 * 
 * Handles all CRUD operations for Merk (Brand) management
 * following Laravel best practices and industry standards.
 */
class MerkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        if ($isApiRequest) {
            // API Response with advanced filtering
            $filters = [
                'search' => $search,
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : null,
                'sort_by' => $request->get('sort_by', MerkColumns::CREATED_AT),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            $query = Merk::searchWithFilters($filters);
            $merks = $query->paginate($request->get('per_page', 15));
            
            return new MerkCollection($merks);
        }

        // Web Response with PDF export support
        $merks = Merk::getAllMerk($search);
        
        if ($request->has('export') && $request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('merk.report', compact('merks'));
            return $pdf->stream('report-merk.pdf');
        }

        return view('merk.index', compact('merks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('merk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMerkRequest $request)
    {
        try {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');

            // Create merk using validated data
            $merk = Merk::create([
                MerkColumns::MERK => $request->input('merk'),
                MerkColumns::IS_ACTIVE => $request->boolean('is_active', true),
            ]);

            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::MERK_CREATED,
                    'data' => new MerkResource($merk)
                ], 201);
            }

            return redirect()->route('merk.index')->with('success', Messages::MERK_CREATED);
            
        } catch (\Exception $e) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $merk = Merk::find($id);

        if (!$merk) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::MERK_NOT_FOUND
                ], 404);
            }
            return abort(404, Messages::MERK_NOT_FOUND);
        }

        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        if ($isApiRequest) {
            return response()->json([
                'success' => true,
                'data' => new MerkResource($merk)
            ]);
        }

        return view('merk.detail', compact('merk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $merk = Merk::find($id);
        if (!$merk) {
            return abort(404, Messages::MERK_NOT_FOUND);
        }
        return view('merk.edit', compact('merk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMerkRequest $request, $id)
    {
        try {
            $merk = Merk::find($id);
            
            if (!$merk) {
                $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
                
                if ($isApiRequest) {
                    return response()->json([
                        'success' => false,
                        'message' => Messages::MERK_NOT_FOUND,
                    ], 404);
                }
                return redirect()->back()->withInput()->with('error', Messages::MERK_NOT_FOUND);
            }

            $merk->update([
                MerkColumns::MERK => $request->input('merk'),
                MerkColumns::IS_ACTIVE => $request->boolean('is_active'),
            ]);

            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::MERK_UPDATED,
                    'data' => new MerkResource($merk->fresh())
                ]);
            }
            
            return redirect()->route('merk.index')->with('success', Messages::MERK_UPDATED);
            
        } catch (\Exception $e) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        $merk = Merk::find($id);

        if (!$merk) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::MERK_NOT_FOUND
                ], 404);
            }
            return redirect()->route('merk.index')->with('error', Messages::MERK_NOT_FOUND);
        }

        $deleted = $merk->delete();

        if ($deleted) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::MERK_DELETED
                ]);
            }
            return redirect()->route('merk.index')->with('success', Messages::MERK_DELETED);
        }

        if ($isApiRequest) {
            return response()->json([
                'success' => false,
                'message' => Messages::MERK_DELETE_FAILED
            ], 422);
        }
        return redirect()->route('merk.index')->with('error', Messages::MERK_DELETE_FAILED);
    }

    /**
     * API-specific endpoints
     */
    public function active(Request $request): JsonResponse
    {
        if ($request->has('per_page')) {
            $merks = Merk::active()
                         ->orderBy(MerkColumns::CREATED_AT, 'desc')
                         ->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => new MerkCollection($merks)
            ]);
        } else {
            $merks = Merk::getActiveMerk();
            return response()->json([
                'success' => true,
                'data' => MerkResource::collection($merks)
            ]);
        }
    }

    public function statistics(Request $request): JsonResponse
    {
        $stats = Merk::getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $filters = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : null,
            'sort_by' => $request->get('sort_by', MerkColumns::CREATED_AT),
            'sort_order' => $request->get('sort_order', 'desc')
        ];

        $query = Merk::searchWithFilters($filters);
        $merks = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => new MerkCollection($merks)
        ]);
    }

    /**
     * DEPRECATED: Keep for backward compatibility - will be removed
     */
    public function getMerkById($id)
    {
        return $this->show(request(), $id);
    }

    public function addMerk(Request $request)
    {
        // Convert to use proper validation
        $validatedData = $request->validate([
            'merk' => 'required|string|max:100',
            'active' => 'nullable|boolean',
        ]);

        return $this->store(new StoreMerkRequest([
            'merk_name' => $validatedData['merk'],
            'is_active' => $validatedData['active'] ?? true
        ]));
    }

    public function updateMerk(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'merk' => 'required|string|max:100',
        ]);

        return $this->update(new UpdateMerkRequest([
            'merk_name' => $validatedData['merk']
        ]), $id);
    }

    public function deleteMerk($id)
    {
        return $this->destroy(request(), $id);
    }
}