<?php

namespace Tests\Feature;

use Tests\TestCase;

class WarehouseDetailViewTest extends TestCase
{
    public function test_warehouse_detail_view_renders_with_data()
    {
        $warehouse = [
            'id' => 1,
            'warehouse_name' => 'WH A',
            'warehouse_address' => 'Address A',
            'warehouse_telephone' => '012345',
            'is_active' => true,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-02',
        ];

        $items = [
            ['id' => 11, 'name' => 'Item X', 'quantity' => 10, 'location' => 'L1', 'comments' => 'c', 'created_at' => '2024-01-04'],
        ];

        $html = view('warehouse.detail', compact('warehouse', 'items'))->render();

        $this->assertStringContainsString('Detail Warehouse', $html);
        $this->assertStringContainsString('WH A', $html);
        $this->assertStringContainsString('Item X', $html);
        $this->assertStringContainsString('Aktif', $html);
    }
}