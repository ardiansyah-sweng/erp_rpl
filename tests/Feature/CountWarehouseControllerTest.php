<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountWarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_warehouse_controller()
    {
        for ($i = 0; $i < 65; $i++) {
            Warehouse::create([
                'warehouse_name' => 'Gudang ' . $i,
                'warehouse_address' => 'Jl. Contoh No. ' . $i,
                'warehouse_telephone' => '08123456789',
                'is_rm_whouse' => 0,
                'is_fg_whouse' => 1,
                'is_active' => 1,
            ]);
        }

        $response = $this->get('/count-warehouse');

        $response->assertStatus(200);

        $response->assertJson([
            'total_warehouse' => 65
        ]);
    }
}