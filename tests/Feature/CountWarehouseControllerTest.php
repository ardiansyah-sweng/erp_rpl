<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;

class CountWarehouseControllerTest extends TestCase
{

    public function test_count_warehouse_controller()
    {

        $response = $this->get('/warehouse/count');

        $response->assertStatus(200);

        $response->assertJson([
            'total_warehouse' => 65
        ]);
    }
}