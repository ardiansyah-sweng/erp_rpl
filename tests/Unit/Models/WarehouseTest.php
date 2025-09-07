<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WarehouseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup database tables
        $this->artisan('migrate');
    }

    /**
     * Test getWarehouseAll() without search parameter
     */
    public function test_get_warehouse_all_returns_all_warehouses()
    {
        // Arrange - Create test warehouses
        $warehouse1 = Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse Alpha',
            WarehouseColumns::ADDRESS => 'Jl. Alpha No. 1',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $warehouse2 = Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse Beta',
            WarehouseColumns::ADDRESS => 'Jl. Beta No. 2',
            WarehouseColumns::PHONE => '021-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Call getWarehouseAll without search
        $result = Warehouse::getWarehouseAll();

        // Assert - Should return paginated results with all warehouses
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertCount(2, $result->items());
        
        // Assert data contains created warehouses
        $warehouseNames = $result->pluck(WarehouseColumns::NAME)->toArray();
        $this->assertContains('Warehouse Alpha', $warehouseNames);
        $this->assertContains('Warehouse Beta', $warehouseNames);
    }

    /**
     * Test getWarehouseAll() with search parameter - search by name
     */
    public function test_get_warehouse_all_with_search_by_name()
    {
        // Arrange - Create test warehouses
        Warehouse::create([
            WarehouseColumns::NAME => 'Central Warehouse Jakarta',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman No. 1',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        Warehouse::create([
            WarehouseColumns::NAME => 'Branch Warehouse Bandung',
            WarehouseColumns::ADDRESS => 'Jl. Asia Afrika No. 2',
            WarehouseColumns::PHONE => '022-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search by name containing 'Jakarta'
        $result = Warehouse::getWarehouseAll('Jakarta');

        // Assert - Should return only warehouses with 'Jakarta' in name
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('Jakarta', $result->first()->warehouse_name);
    }

    /**
     * Test getWarehouseAll() with search parameter - search by address
     */
    public function test_get_warehouse_all_with_search_by_address()
    {
        // Arrange - Create test warehouses
        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse A',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman Jakarta Pusat',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse B',
            WarehouseColumns::ADDRESS => 'Jl. Asia Afrika Bandung',
            WarehouseColumns::PHONE => '022-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search by address containing 'Sudirman'
        $result = Warehouse::getWarehouseAll('Sudirman');

        // Assert - Should return only warehouses with 'Sudirman' in address
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('Sudirman', $result->first()->warehouse_address);
    }

    /**
     * Test getWarehouseAll() with search parameter - search by phone
     */
    public function test_get_warehouse_all_with_search_by_phone()
    {
        // Arrange - Create test warehouses
        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse A',
            WarehouseColumns::ADDRESS => 'Jl. Test A',
            WarehouseColumns::PHONE => '021-1234567',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse B',
            WarehouseColumns::ADDRESS => 'Jl. Test B',
            WarehouseColumns::PHONE => '022-7654321',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search by phone containing '021'
        $result = Warehouse::getWarehouseAll('021');

        // Assert - Should return only warehouses with '021' in phone
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('021', $result->first()->warehouse_telephone);
    }

    /**
     * Test getWarehouseAll() with search that returns no results
     */
    public function test_get_warehouse_all_with_search_no_results()
    {
        // Arrange - Create test warehouse
        Warehouse::create([
            WarehouseColumns::NAME => 'Test Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Test',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search with keyword that doesn't exist
        $result = Warehouse::getWarehouseAll('NonExistentKeyword');

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test getWarehouseAll() returns paginated results
     */
    public function test_get_warehouse_all_returns_paginated_results()
    {
        // Arrange - Create multiple warehouses
        for ($i = 1; $i <= 20; $i++) {
            Warehouse::create([
                WarehouseColumns::NAME => "Warehouse {$i}",
                WarehouseColumns::ADDRESS => "Jl. Test {$i}",
                WarehouseColumns::PHONE => "021-{$i}111111",
                WarehouseColumns::IS_RM_WAREHOUSE => $i % 2 === 0,
                WarehouseColumns::IS_FG_WAREHOUSE => $i % 2 === 1,
                WarehouseColumns::IS_ACTIVE => true,
            ]);
        }

        // Act - Get paginated results
        $result = Warehouse::getWarehouseAll();

        // Assert - Should return paginated results
        $this->assertNotNull($result);
        $this->assertEquals(20, $result->total());
        
        // Assert pagination metadata exists
        $this->assertNotNull($result->currentPage());
        $this->assertNotNull($result->perPage());
        $this->assertNotNull($result->lastPage());
    }

    /**
     * Test getWarehouseAll() with empty database
     */
    public function test_get_warehouse_all_with_empty_database()
    {
        // Act - Call getWarehouseAll with empty database
        $result = Warehouse::getWarehouseAll();

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test getWarehouseAll() ordering - should be ordered by created_at asc
     */
    public function test_get_warehouse_all_ordering()
    {
        // Arrange - Create warehouses with different timestamps
        $warehouse1 = Warehouse::create([
            WarehouseColumns::NAME => 'First Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. First',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Small delay to ensure different timestamps
        sleep(1);

        $warehouse2 = Warehouse::create([
            WarehouseColumns::NAME => 'Second Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Second',
            WarehouseColumns::PHONE => '021-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Get all warehouses
        $result = Warehouse::getWarehouseAll();

        // Assert - Should be ordered by created_at ascending (oldest first)
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertEquals('First Warehouse', $result->first()->warehouse_name);
        $this->assertEquals('Second Warehouse', $result->last()->warehouse_name);
    }

}