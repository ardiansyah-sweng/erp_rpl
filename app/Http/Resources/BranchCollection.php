<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Constants\BranchColumns;

class BranchCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => $this->getMeta(),
            'summary' => $this->getSummary(),
        ];
    }
    
    /**
     * Get metadata information
     */
    private function getMeta(): array
    {
        return [
            'total' => $this->count(),
            'active_count' => $this->collection->where(BranchColumns::IS_ACTIVE, true)->count(),
            'inactive_count' => $this->collection->where(BranchColumns::IS_ACTIVE, false)->count(),
            'percentage_active' => $this->getActivePercentage(),
        ];
    }
    
    /**
     * Get summary statistics
     */
    private function getSummary(): array
    {
        return [
            'status_distribution' => [
                'active' => [
                    'count' => $this->collection->where(BranchColumns::IS_ACTIVE, true)->count(),
                    'label' => 'Cabang Aktif'
                ],
                'inactive' => [
                    'count' => $this->collection->where(BranchColumns::IS_ACTIVE, false)->count(),
                    'label' => 'Cabang Tidak Aktif'
                ]
            ],
            'cities' => $this->getCityDistribution(),
            'latest_branch' => $this->getLatestBranch(),
        ];
    }
    
    /**
     * Get active percentage
     */
    private function getActivePercentage(): float
    {
        $total = $this->count();
        if ($total === 0) return 0;
        
        $active = $this->collection->where(BranchColumns::IS_ACTIVE, true)->count();
        return round(($active / $total) * 100, 2);
    }
    
    /**
     * Get city distribution
     */
    private function getCityDistribution(): array
    {
        $cities = [];
        
        foreach ($this->collection as $branch) {
            $name = $branch->{BranchColumns::NAME};
            
            // Extract city from branch name (assuming format: "Cabang [City] [Direction]")
            if (preg_match('/Cabang\s+(\w+)/', $name, $matches)) {
                $city = $matches[1];
                $cities[$city] = ($cities[$city] ?? 0) + 1;
            }
        }
        
        // Sort by count descending
        arsort($cities);
        
        return array_map(function($count, $city) {
            return [
                'city' => $city,
                'count' => $count,
                'label' => "$city ($count cabang)"
            ];
        }, $cities, array_keys($cities));
    }
    
    /**
     * Get latest branch info
     */
    private function getLatestBranch(): ?array
    {
        $latest = $this->collection->sortByDesc('created_at')->first();
        
        if (!$latest) {
            return null;
        }
        
        return [
            'name' => $latest->{BranchColumns::NAME},
            'created_at' => $latest->created_at?->toDateTimeString(),
            'created_at_human' => $latest->created_at?->diffForHumans(),
        ];
    }

    /**
     * Get additional data when requested
     */
    public function with($request)
    {
        return [
            'links' => [
                'self' => route('api.branches.index'),
                'create' => route('api.branches.store'),
                'active_only' => route('api.branches.active'),
            ],
            'filters' => [
                'available' => [
                    'status' => ['active', 'inactive'],
                    'search' => 'Search by name, address, or phone',
                ]
            ],
        ];
    }
}
