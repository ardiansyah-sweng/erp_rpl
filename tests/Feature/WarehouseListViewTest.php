<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;

class WarehouseListViewTest extends TestCase
{
    public function test_warehouse_index_view_renders_correctly()
    {
        // Create some warehouses
        $warehouses = Warehouse::factory()->count(2)->create();

        // Test the index view which lists warehouses
        $response = $this->get('/warehouses');
        
        $response->assertStatus(200);
        foreach ($warehouses as $wh) {
            $response->assertSee($wh->warehouse_name);
        }
    }

    public function test_warehouse_create_form_renders()
    {
        // Test that the warehouse creation form view renders
        $response = $this->get('/warehouses/create');
        
        $response->assertStatus(200);
    }
}
