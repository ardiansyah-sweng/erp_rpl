<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Merk;

/**
 * MerkCollection
 * 
 * Transforms a collection of Merk models into a paginated API response
 * with comprehensive metadata and statistics.
 */
class MerkCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($merk) {
                return new MerkResource($merk);
            }),
            
            // Pagination metadata
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                
                // Collection statistics
                'statistics' => $this->getCollectionStatistics(),
                
                // Filter information
                'filters' => $this->getAppliedFilters($request),
                
                // Quick access links
                'links' => $this->getQuickLinks($request),
                
                // Response metadata
                'generated_at' => now()->toISOString(),
                'version' => '1.0',
            ],
            
            // Pagination links
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }

    /**
     * Get statistics for the current collection.
     */
    private function getCollectionStatistics(): array
    {
        $active = $this->collection->where('is_active', true)->count();
        $inactive = $this->collection->where('is_active', false)->count();
        $total = $this->collection->count();
        
        return [
            'total_in_page' => $total,
            'active_in_page' => $active,
            'inactive_in_page' => $inactive,
            'percentage_active_in_page' => $total > 0 ? round(($active / $total) * 100, 2) : 0,
            
            // Overall statistics (from database)
            'overall' => Merk::getStatistics(),
        ];
    }

    /**
     * Get applied filters information.
     */
    private function getAppliedFilters(Request $request): array
    {
        return [
            'search' => $request->get('search'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'is_active' => $request->get('is_active'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'per_page' => $request->get('per_page', 15),
        ];
    }

    /**
     * Get quick access links for common filters.
     */
    private function getQuickLinks(Request $request): array
    {
        $baseUrl = $request->url();
        $baseParams = $request->only(['search', 'name', 'description', 'sort_by', 'sort_order', 'per_page']);
        
        return [
            'all' => $baseUrl . '?' . http_build_query(array_merge($baseParams, ['is_active' => null])),
            'active' => $baseUrl . '?' . http_build_query(array_merge($baseParams, ['is_active' => 1])),
            'inactive' => $baseUrl . '?' . http_build_query(array_merge($baseParams, ['is_active' => 0])),
            'recent' => $baseUrl . '?' . http_build_query(array_merge($baseParams, ['sort_by' => 'created_at', 'sort_order' => 'desc'])),
            'alphabetical' => $baseUrl . '?' . http_build_query(array_merge($baseParams, ['sort_by' => 'merk_name', 'sort_order' => 'asc'])),
        ];
    }

    /**
     * Additional data to be added to the collection response.
     */
    public function with(Request $request): array
    {
        return [
            'message' => 'Merk data retrieved successfully',
            'status' => 'success',
        ];
    }
}
