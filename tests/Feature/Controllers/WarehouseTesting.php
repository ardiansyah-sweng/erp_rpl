<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTesting extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-WH-23
     * Test: View warehouse detail with valid ID
     */
    public function test_view_warehouse_detail_with_valid_id()
    {
        // 🧩 ARRANGE - Prepare test data
        $warehouse = Warehouse::factory()->create([
            WarehouseColumns::NAME => 'Main Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta',
            WarehouseColumns::PHONE => '021-1234567',
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // 🚀 ACT - Execute the test
        $response = $this->getJson("/api/warehouses/{$warehouse->id}");

        // ✅ ASSERT - Verify the results
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'warehouse_name',
                         'warehouse_address',
                         'warehouse_phone',
                         'is_rm_warehouse',
                         'is_fg_warehouse',
                         'is_active',
                         'created_at',
                         'updated_at',
                     ],
                 ])
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $warehouse->id,
                         'warehouse_name' => 'Main Warehouse',
                         'warehouse_address' => 'Jl. Sudirman No. 123, Jakarta',
                         'warehouse_phone' => '021-1234567',
                         'is_active' => true,
                     ],
                 ]);
    }

    /**
     * TC-WH-24 (Additional)
     * Test: View warehouse detail with invalid ID
     * 
     * Expected Result: Returns 404 not found
     */
    public function test_view_warehouse_detail_with_invalid_id()
    {
        // 🧩 ARRANGE - No warehouse created, use non-existent ID
        $invalidId = 99999;

        // 🚀 ACT - Try to get non-existent warehouse
        $response = $this->getJson("/api/warehouses/{$invalidId}");

        // ✅ ASSERT - Should return 404
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Gudang tidak ditemukan',
                 ]);
    }
}