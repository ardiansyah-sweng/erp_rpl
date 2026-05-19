<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Constants\WarehouseColumns;

class WarehouseCollection extends ResourceCollection
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
            'active_count' => $this->collection->where(WarehouseColumns::IS_ACTIVE, true)->count(),
            'inactive_count' => $this->collection->where(WarehouseColumns::IS_ACTIVE, false)->count(),
            'rm_warehouse_count' => $this->collection->where(WarehouseColumns::IS_RM_WAREHOUSE, true)->count(),
            'fg_warehouse_count' => $this->collection->where(WarehouseColumns::IS_FG_WAREHOUSE, true)->count(),
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
                    'count' => $this->collection->where(WarehouseColumns::IS_ACTIVE, true)->count(),
                    'label' => 'Warehouse Aktif'
                ],
                'inactive' => [
                    'count' => $this->collection->where(WarehouseColumns::IS_ACTIVE, false)->count(),
                    'label' => 'Warehouse Tidak Aktif'
                ]
            ],
            'type_distribution' => [
                'raw_material' => [
                    'count' => $this->collection->where(WarehouseColumns::IS_RM_WAREHOUSE, true)->count(),
                    'label' => 'Raw Material Warehouse'
                ],
                'finished_goods' => [
                    'count' => $this->collection->where(WarehouseColumns::IS_FG_WAREHOUSE, true)->count(),
                    'label' => 'Finished Goods Warehouse'
                ],
                'both' => [
                    'count' => $this->collection->where(function($item) {
                        return $item->{WarehouseColumns::IS_RM_WAREHOUSE} && $item->{WarehouseColumns::IS_FG_WAREHOUSE};
                    })->count(),
                    'label' => 'Multi-Purpose Warehouse'
                ]
            ],
            'locations' => $this->getLocationDistribution(),
            'latest_warehouse' => $this->getLatestWarehouse(),
        ];
    }
    
    /**
     * Get active percentage
     */
    private function getActivePercentage(): float
    {
        $total = $this->count();
        if ($total === 0) {
            return 0;
        }
        
        $active = $this->collection->where(WarehouseColumns::IS_ACTIVE, true)->count();
        return round(($active / $total) * 100, 2);
    }
    
    /**
     * Get location distribution
     */
    private function getLocationDistribution(): array
    {
        $locations = [];
        
        foreach ($this->collection as $warehouse) {
            $address = $warehouse->{WarehouseColumns::ADDRESS} ?? '';
            
            // Extract city/location from address
            $words = explode(' ', trim($address));
            if (!empty($words)) {
                // Take first significant word as location
                $location = $words[0];
                $locations[$location] = ($locations[$location] ?? 0) + 1;
            }
        }
        
        // Sort by count descending
        arsort($locations);
        
        return array_slice(array_map(function($count, $location) {
            return [
                'location' => $location,
                'count' => $count,
                'label' => "$location ($count warehouse)"
            ];
        }, $locations, array_keys($locations)), 0, 5); // Top 5 locations
    }
    
    /**
     * Get latest warehouse info
     */
    private function getLatestWarehouse(): ?array
    {
        $latest = $this->collection->sortByDesc('created_at')->first();
        
        if (!$latest) {
            return null;
        }
        
        return [
            'name' => $latest->{WarehouseColumns::NAME},
            'address' => $latest->{WarehouseColumns::ADDRESS},
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
                'self' => route('api.warehouses.index'),
                'create' => route('api.warehouses.store'),
                'active_only' => route('api.warehouses.index', ['status' => 'active']),
                'rm_warehouses' => route('api.warehouses.index', ['type' => 'rm']),
                'fg_warehouses' => route('api.warehouses.index', ['type' => 'fg']),
            ],
            'filters' => [
                'available' => [
                    'status' => ['active', 'inactive'],
                    'type' => ['rm', 'fg', 'both'],
                    'search' => 'Search by name, address, or phone',
                ]
            ],
        ];
    }
}
