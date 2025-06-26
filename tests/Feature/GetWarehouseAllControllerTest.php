<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;

class GetWarehouseAllControllerTest extends TestCase
{
    public function test_get_warehouse_all_controller()
    {
        $response = $this->get('/warehouse/all');

        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }
}