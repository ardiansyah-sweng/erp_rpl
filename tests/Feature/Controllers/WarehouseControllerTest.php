<?php

namespace Tests\Feature\Controllers;

use App\Constants\WarehouseColumns;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for WarehouseController.
 */
class WarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test searchWarehouse() by name.
     * @test
     */
    public function test_search_warehouse_by_name()
    {
        // Arrange: Create warehouses with distinct names
        Warehouse::factory()->create([WarehouseColumns::NAME => 'Gudang Pusat Jakarta']);
        Warehouse::factory()->create([WarehouseColumns::NAME => 'Gudang Cabang Surabaya']);

        // Act: Call the search route with a name keyword
        $response = $this->getJson(route('warehouse.search', ['name' => 'Jakarta']));

        // Assert: Check for correct response and data
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['warehouse_name' => 'Gudang Pusat Jakarta']);
    }

    /**
     * Test searchWarehouse() by address.
     * @test
     */
    public function test_search_warehouse_by_address()
    {
        // Arrange: Create warehouses with distinct addresses
        Warehouse::factory()->create([WarehouseColumns::ADDRESS => 'Jl. Sudirman, Jakarta']);
        Warehouse::factory()->create([WarehouseColumns::ADDRESS => 'Jl. Asia Afrika, Bandung']);

        // Act: Call the search route with an address keyword
        $response = $this->getJson(route('warehouse.search', ['address' => 'Sudirman']));

        // Assert: Check for correct response and data
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['warehouse_address' => 'Jl. Sudirman, Jakarta']);
    }

    /**
     * Test searchWarehouse() by phone number.
     * @test
     */
    public function test_search_warehouse_by_phone()
    {
        // Arrange: Create warehouses with distinct phone numbers
        Warehouse::factory()->create([WarehouseColumns::PHONE => '021-12345678']);
        Warehouse::factory()->create([WarehouseColumns::PHONE => '022-87654321']);

        // Act: Call the search route with a phone keyword
        $response = $this->getJson(route('warehouse.search', ['phone' => '12345']));

        // Assert: Check for correct response and data
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['warehouse_phone' => '021-12345678']);
    }

    /**
     * Test searchWarehouse() with a keyword that yields no results.
     * @test
     */
    public function test_search_warehouse_returns_no_results()
    {
        // Arrange: Create a warehouse
        Warehouse::factory()->create([WarehouseColumns::NAME => 'Gudang Test']);

        // Act: Call the search route with a non-matching keyword
        $response = $this->getJson(route('warehouse.search', ['name' => 'NonExistent']));

        // Assert: Check for an empty data array
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }

    /**
     * Test searchWarehouse() on an empty database.
     * @test
     */
    public function test_search_warehouse_on_empty_database()
    {
        // Act: Call the search route when no warehouses exist
        $response = $this->getJson(route('warehouse.search', ['name' => 'any']));

        // Assert: Check for an empty data array
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }

    /**
     * Test that search returns a WarehouseCollection structure.
     * @test
     */
    public function test_search_returns_collection_structure()
    {
        // Arrange
        Warehouse::factory()->create();

        // Act
        $response = $this->getJson(route('warehouse.search', ['name' => 'Gudang']));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'warehouse_name',
                        'warehouse_address',
                        'status',
                        'warehouse_type'
                    ]
                ],
                'meta',
                'summary'
            ]);
    }
}