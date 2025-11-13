<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseCollection;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Constants\Messages;
use App\Constants\WarehouseColumns;

class WarehouseController extends Controller
{

    /** Display a listing of the resource. */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Force JSON response for API routes (check if route starts with 'api.')
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        //Use enhanced query for API requests
        if ($isApiRequest) {
            // Best Practice: Use Model method instead of Controller query
            $filters = [
                'search' => $search,
                'status' => $request->get('status'),
                'sort_by' => $request->get('sort_by', WarehouseColumns::CREATED_AT),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            $query = Warehouse::searchWithFilters($filters);
            $warehouses = $query->paginate($request->get('per_page', 15));
            return new WarehouseCollection($warehouses);
        }

        // Web functionality - use existing logic
        $warehouses = Warehouse::getWarehouseAll($search);

        // Handle PDF Export (existing functionality)
        if ($request->has('export') && $request->input('export') === 'pdf'){
            $pdf = Pdf::loadView('warehouse.report', ['warehouses' => $warehouses]);
            return $pdf->stream('report-warehouse.pdf');
        }

        return view('warehouse.index', ['warehouses' => $warehouses]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('warehouse.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        try {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');

            // Create warehouse using direct Eloquent
            $warehouse = Warehouse::create([
                WarehouseColumns::NAME => $request->input('warehouse_name'),
                WarehouseColumns::ADDRESS => $request->input('warehouse_address'),
                WarehouseColumns::PHONE => $request->input('warehouse_phone'),
                WarehouseColumns::IS_ACTIVE => $request->boolean('is_active'),
                WarehouseColumns::IS_RM_WAREHOUSE => $request->boolean('is_rm_warehouse'),
                WarehouseColumns::IS_FG_WAREHOUSE => $request->boolean('is_fg_warehouse'),
            ]);

            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::WAREHOUSE_CREATED,
                    'data' => new WarehouseResource($warehouse)
                ], 201);
            }

            return redirect()->route('warehouses.index')->with('success', Messages::WAREHOUSE_CREATED);
            
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
     * Show the form for editing the specified warehouse.
     */
    public function edit($id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return abort(404, Messages::WAREHOUSE_NOT_FOUND);
        }
        return view('warehouse.edit', compact('warehouse'));
    }

    /**
     * Display the specified resource (API endpoint)
     */
    public function show(Request $request, $id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::WAREHOUSE_NOT_FOUND
                ], 404);
            }
            return abort(404, Messages::WAREHOUSE_NOT_FOUND);
        }

        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        if ($isApiRequest) {
            return response()->json([
                'success' => true,
                'data' => new WarehouseResource($warehouse)
            ]);
        }

        return view('warehouse.detail', compact('warehouse'));
    }

    /**
     * DEPRECATED: Keep for backward compatibility - will be removed
     */
    public function getWarehouseById($id)
    {
        return $this->show(request(), $id);
    }

    /**
     * DEPRECATED: Use statistics() method instead
     */
    public function countWarehouse()
    {
        $total = Warehouse::count();

        return response()->json([
            'total_warehouse' => $total
        ]);
    }

    /**
     * DEPRECATED: Use search() method instead
     */
    public function searchWarehouse(Request $request)
    {
        return $this->search($request);
    }

    /**
     * DEPRECATED: Keep for backward compatibility - will be removed
     */
    public function deleteWarehouse($id)
    {
        return $this->destroy(request(), $id);
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Request $request, $id)
    {
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::WAREHOUSE_NOT_FOUND
                ], 404);
            }
            return redirect()->route('warehouses.index')->with('error', Messages::WAREHOUSE_NOT_FOUND);
        }

        // Validasi relasi dengan try-catch agar tidak error jika tabel belum ada
        $stockExists = false;
        $inventoryExists = false;
        try {
            // Check if warehouse has stock records
            $stockExists = \DB::table('stock')->where('warehouse_id', $id)->exists();
        } catch (\Exception $e) {
            $stockExists = false;
        }
        try {
            // Check if warehouse has inventory records
            $inventoryExists = \DB::table('material_inventory')->where('warehouse_id', $id)->exists();
        } catch (\Exception $e) {
            $inventoryExists = false;
        }

        if ($stockExists || $inventoryExists) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::WAREHOUSE_IN_USE
                ], 422);
            }
            return redirect()->route('warehouses.index')->with('error', Messages::WAREHOUSE_IN_USE);
        }

        $deleted = $warehouse->delete();

        if ($deleted) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::WAREHOUSE_DELETED
                ]);
            }
            return redirect()->route('warehouses.index')->with('success', Messages::WAREHOUSE_DELETED);
        }

        // Gagal hapus warehouse
        if ($isApiRequest) {
            return response()->json([
                'success' => false,
                'message' => Messages::WAREHOUSE_DELETE_FAILED
            ], 422);
        }
        return redirect()->route('warehouses.index')->with('error', Messages::WAREHOUSE_DELETE_FAILED);
    }

    /**
     * DEPRECATED: Use proper export functionality instead
     */
    public function exportPdf()
    {
        $warehouses = Warehouse::orderBy(WarehouseColumns::CREATED_AT, 'desc')->get();
        
        $pdf = Pdf::loadView('warehouse.report', compact('warehouses'));
        return $pdf->stream('warehouse_report.pdf');
    }

    /**
     * DEPRECATED: Keep for backward compatibility - will be removed
     */
    public function getWarehouseAll()
    {
        return $this->index(request());
    }

    /**
        * Update the specified warehouse in storage.
    */
    public function update(UpdateWarehouseRequest $request, $id)
    {
        try {
            $warehouse = Warehouse::find($id);
            
            if (!$warehouse) {
                $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
                
                if ($isApiRequest) {
                    return response()->json([
                        'success' => false,
                        'message' => Messages::WAREHOUSE_NOT_FOUND,
                    ], 404);
                }
                return redirect()->back()->withInput()->with('error', Messages::WAREHOUSE_NOT_FOUND);
            }

            $warehouse->update([
                'warehouse_name' => $request->input('warehouse_name'),
                'warehouse_address' => $request->input('warehouse_address'),
                'warehouse_phone' => $request->input('warehouse_phone'),
                'is_rm_warehouse' => $request->boolean('is_rm_warehouse'),
                'is_fg_warehouse' => $request->boolean('is_fg_warehouse'),
                'is_active' => $request->boolean('is_active'),
            ]);

            // Handle API Response
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
            
            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::WAREHOUSE_UPDATED,
                    'data' => new WarehouseResource($warehouse->fresh())
                ]);
            }

            // Handle Web Response
            return redirect()->route('warehouses.index')->with('success', Messages::WAREHOUSE_UPDATED);
            
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
     * API-specific endpoints
     */
    public function active(Request $request)
    {
        // Best Practice: Use Model method
        if ($request->has('per_page')) {
            $warehouses = Warehouse::where(WarehouseColumns::IS_ACTIVE, true)
                                  ->orderBy(WarehouseColumns::CREATED_AT, 'desc')
                                  ->paginate($request->get('per_page', 15));
        } else {
            $warehouses = Warehouse::where(WarehouseColumns::IS_ACTIVE, true)
                                  ->orderBy(WarehouseColumns::CREATED_AT, 'desc')
                                  ->get();
        }

        return new WarehouseCollection($warehouses);
    }

    public function statistics(Request $request)
    {
        // Best Practice: Use Model method for statistics
        $stats = [
            'total_warehouses' => Warehouse::count(),
            'active_warehouses' => Warehouse::where(WarehouseColumns::IS_ACTIVE, true)->count(),
            'inactive_warehouses' => Warehouse::where(WarehouseColumns::IS_ACTIVE, false)->count(),
            'rm_warehouses' => Warehouse::where(WarehouseColumns::IS_RM_WAREHOUSE, true)->count(),
            'fg_warehouses' => Warehouse::where(WarehouseColumns::IS_FG_WAREHOUSE, true)->count(),
        ];

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
            'is_rm_warehouse' => $request->has('is_rm_warehouse') ? $request->boolean('is_rm_warehouse') : null,
            'is_fg_warehouse' => $request->has('is_fg_warehouse') ? $request->boolean('is_fg_warehouse') : null,
            'sort_by' => WarehouseColumns::CREATED_AT,
            'sort_order' => 'desc'
        ];

        $query = Warehouse::searchWithFilters($filters);
        $warehouses = $query->paginate($request->get('per_page', 15));

        return new WarehouseCollection($warehouses);
    }
}
