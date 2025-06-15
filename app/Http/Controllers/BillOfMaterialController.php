<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function index(Request $request)
{
    $dummyData = collect();

    for ($i = 1; $i <= 25; $i++) {
        $dummyData->push((object)[
            'id' => $i,
            'bom_id' => 'BOM-' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'bom_name' => 'Material ' . chr(64 + $i), 
            'measurement_unit' => rand(1,50),
            'total_cost' => rand(10000, 50000),
            'active' => 1,
            'created_at' => now()->subDays(rand(1, 10))->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    $perPage = 10;
    $currentPage = Paginator::resolveCurrentPage();
    $pagedData = $dummyData->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginated = new LengthAwarePaginator(
        $pagedData,
        $dummyData->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('billOfMaterial.list', [
        'billOfMaterial' => $paginated
    ]);
}

}
