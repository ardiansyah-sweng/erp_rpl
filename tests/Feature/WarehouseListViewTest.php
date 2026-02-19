<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;

class WarehouseListViewTest extends TestCase
{
    public function test_warehouse_list_view_renders_warehouses()
    {
        // Create some warehouses
        $warehouses = Warehouse::factory()->count(2)->create();

        // Render the view directly
        $rendered = view('warehouse.list', ['warehouses' => $warehouses])->render();

        $this->assertStringContainsString('List Warehouse', $rendered);
        foreach ($warehouses as $wh) {
            $this->assertStringContainsString($wh->warehouse_name, $rendered);
        }
    }
}
