<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\WarehouseColumns;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'warehouse_name' => $this->{WarehouseColumns::NAME},
            'warehouse_address' => $this->{WarehouseColumns::ADDRESS},
            'warehouse_phone' => $this->{WarehouseColumns::PHONE},
            'is_rm_warehouse' => (bool) $this->{WarehouseColumns::IS_RM_WAREHOUSE},
            'is_fg_warehouse' => (bool) $this->{WarehouseColumns::IS_FG_WAREHOUSE},
            'is_active' => (bool) $this->{WarehouseColumns::IS_ACTIVE},
            
            // Status and type information
            'status' => $this->getStatusText(),
            'status_badge' => $this->getStatusBadge(),
            'warehouse_type' => $this->getWarehouseType(),
            'type_badge' => $this->getTypeBadge(),
            
            // Formatted data
            'display_name' => $this->getDisplayName(),
            'short_address' => $this->getShortAddress(),
            'formatted_phone' => $this->getFormattedPhone(),
            
            // Timestamps
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),
            
            // Conditional fields (only include when requested)
            'detailed_info' => $this->when($request->has('include_details'), [
                'created_by' => $this->created_by ?? 'System',
                'last_modified' => $this->updated_at?->format('d/m/Y H:i:s'),
                'age_in_days' => $this->created_at?->diffInDays(now()),
                'capabilities' => $this->getCapabilities(),
            ]),
        ];
    }
    
    /**
     * Get status text in Indonesian
     */
    private function getStatusText(): string
    {
        return $this->{WarehouseColumns::IS_ACTIVE} ? 'Aktif' : 'Tidak Aktif';
    }
    
    /**
     * Get status badge info for frontend
     */
    private function getStatusBadge(): array
    {
        return [
            'text' => $this->getStatusText(),
            'color' => $this->{WarehouseColumns::IS_ACTIVE} ? 'success' : 'danger',
            'icon' => $this->{WarehouseColumns::IS_ACTIVE} ? 'check-circle' : 'x-circle',
        ];
    }
    
    /**
     * Get warehouse type text
     */
    private function getWarehouseType(): string
    {
        $isRm = $this->{WarehouseColumns::IS_RM_WAREHOUSE};
        $isFg = $this->{WarehouseColumns::IS_FG_WAREHOUSE};
        
        if ($isRm && $isFg) {
            return 'Multi-Purpose';
        } elseif ($isRm) {
            return 'Raw Material';
        } elseif ($isFg) {
            return 'Finished Goods';
        } else {
            return 'General';
        }
    }
    
    /**
     * Get type badge info for frontend
     */
    private function getTypeBadge(): array
    {
        $type = $this->getWarehouseType();
        
        $colorMap = [
            'Multi-Purpose' => 'primary',
            'Raw Material' => 'warning',
            'Finished Goods' => 'info',
            'General' => 'secondary',
        ];
        
        $iconMap = [
            'Multi-Purpose' => 'layers',
            'Raw Material' => 'box',
            'Finished Goods' => 'package',
            'General' => 'building',
        ];
        
        return [
            'text' => $type,
            'color' => $colorMap[$type] ?? 'secondary',
            'icon' => $iconMap[$type] ?? 'building',
        ];
    }
    
    /**
     * Get display name with status and type indicators
     */
    private function getDisplayName(): string
    {
        $status = $this->{WarehouseColumns::IS_ACTIVE} ? '✅' : '❌';
        $type = '';
        
        if ($this->{WarehouseColumns::IS_RM_WAREHOUSE}) {
            $type .= '📦'; // Raw material icon
        }
        if ($this->{WarehouseColumns::IS_FG_WAREHOUSE}) {
            $type .= '📋'; // Finished goods icon
        }
        
        return $status . ' ' . $type . ' ' . $this->{WarehouseColumns::NAME};
    }
    
    /**
     * Get shortened address for list display
     */
    private function getShortAddress(): string
    {
        $address = $this->{WarehouseColumns::ADDRESS} ?? '';
        return strlen($address) > 50 ? substr($address, 0, 47) . '...' : $address;
    }
    
    /**
     * Get formatted phone number
     */
    private function getFormattedPhone(): string
    {
        $phone = $this->{WarehouseColumns::PHONE} ?? '';
        
        // If already formatted or empty, return as is
        if (empty($phone) || strpos($phone, '-') !== false) {
            return $phone;
        }
        
        // Try to format Indonesian phone numbers
        if (preg_match('/^(\d{3,4})(\d{8})$/', $phone, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }
        
        return $phone;
    }
    
    /**
     * Get warehouse capabilities
     */
    private function getCapabilities(): array
    {
        $capabilities = [];
        
        if ($this->{WarehouseColumns::IS_RM_WAREHOUSE}) {
            $capabilities[] = 'Raw Material Storage';
        }
        
        if ($this->{WarehouseColumns::IS_FG_WAREHOUSE}) {
            $capabilities[] = 'Finished Goods Storage';
        }
        
        if (empty($capabilities)) {
            $capabilities[] = 'General Storage';
        }
        
        return $capabilities;
    }

    /**
     * Get additional data when requested
     */
    public function with($request)
    {
        return [
            'links' => [
                'self' => route('api.warehouses.show', $this->id),
                'edit' => route('api.warehouses.update', $this->id),
                'delete' => route('api.warehouses.destroy', $this->id),
            ],
        ];
    }
}
