<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\BranchColumns;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'branch_name' => $this->{BranchColumns::NAME},
            'branch_address' => $this->{BranchColumns::ADDRESS},
            'branch_telephone' => $this->{BranchColumns::PHONE},
            'is_active' => (bool) $this->{BranchColumns::IS_ACTIVE},
            'status' => $this->getStatusText(),
            'status_badge' => $this->getStatusBadge(),
            
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
            ]),
        ];
    }
    
    /**
     * Get status text in Indonesian
     */
    private function getStatusText(): string
    {
        return $this->{BranchColumns::IS_ACTIVE} ? 'Aktif' : 'Tidak Aktif';
    }
    
    /**
     * Get status badge info for frontend
     */
    private function getStatusBadge(): array
    {
        return [
            'text' => $this->getStatusText(),
            'color' => $this->{BranchColumns::IS_ACTIVE} ? 'success' : 'danger',
            'icon' => $this->{BranchColumns::IS_ACTIVE} ? 'check-circle' : 'x-circle',
        ];
    }
    
    /**
     * Get display name with status indicator
     */
    private function getDisplayName(): string
    {
        $status = $this->{BranchColumns::IS_ACTIVE} ? '✅' : '❌';
        return $status . ' ' . $this->{BranchColumns::NAME};
    }
    
    /**
     * Get shortened address for list display
     */
    private function getShortAddress(): string
    {
        $address = $this->{BranchColumns::ADDRESS};
        return strlen($address) > 50 ? substr($address, 0, 47) . '...' : $address;
    }
    
    /**
     * Get formatted phone number
     */
    private function getFormattedPhone(): string
    {
        $phone = $this->{BranchColumns::PHONE};
        
        // If already formatted, return as is
        if (strpos($phone, '-') !== false) {
            return $phone;
        }
        
        // Try to format Indonesian phone numbers
        if (preg_match('/^(\d{3,4})(\d{8})$/', $phone, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }
        
        return $phone;
    }

    /**
     * Get additional data when requested
     */
    public function with($request)
    {
        return [
            'links' => [
                'self' => route('api.branches.show', $this->id),
                'edit' => route('api.branches.update', $this->id),
                'delete' => route('api.branches.destroy', $this->id),
            ],
        ];
    }
}
