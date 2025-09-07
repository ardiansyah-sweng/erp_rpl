<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MerkResource
 * 
 * Transforms Merk model data into a consistent API response format
 * following industry best practices for API resource transformation.
 */
class MerkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'merk' => $this->merk,
            'is_active' => $this->is_active,
            
            // Computed attributes for UI display
            'status_label' => $this->status_label,
            'display_name' => $this->display_name,
            
            // Status badge for frontend
            'status_badge' => [
                'text' => $this->status_label,
                'class' => $this->is_active ? 'badge bg-success' : 'badge bg-danger',
                'icon' => $this->is_active ? 'bi bi-check-circle' : 'bi bi-x-circle'
            ],
            
            // Formatted dates
            'created_at' => $this->created_at?->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'created_at_iso' => $this->created_at?->toISOString(),
            'updated_at_iso' => $this->updated_at?->toISOString(),
            
            // Human readable timestamps
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),
            
            // Additional metadata
            'meta' => [
                'can_edit' => true, // Could be based on permissions
                'can_delete' => true, // Could check for relationships
                'has_description' => !empty($this->merk_description),
            ],
        ];
    }

    /**
     * Additional data to be added to the resource response.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
}
