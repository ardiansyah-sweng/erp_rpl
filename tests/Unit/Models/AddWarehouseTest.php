<?php

/** php artisan /test/Unit/Models/AddWarehouseTest.php --filter=test_add_warehouse_success **/
namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddWarehouseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_add_warehouse_success()
    {
        $data = [
            WarehouseColumns::NAME => 'New Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Test No. 123',
            WarehouseColumns::PHONE => '021-9999999',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ];

        $warehouse = Warehouse::addWarehouse($data);

        $this->assertNotNull($warehouse);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            WarehouseColumns::NAME => 'New Warehouse',
        ]);
    }

    /**
     * Test addWarehouse() fails with empty data
     */
    public function test_add_warehouse_fails_with_empty_data()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Act
        Warehouse::addWarehouse([]);
    }

    /**
     * Test addWarehouse() with minimal required fields
     */
    public function test_add_warehouse_with_minimal_fields()
    {
        // Arrange
        $data = [
            WarehouseColumns::NAME => 'Minimal Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Minimal',
            WarehouseColumns::PHONE => '021-123123',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ];

        // Act
        $warehouse = Warehouse::addWarehouse($data);

        // Assert
        $this->assertNotNull($warehouse->id);
        $this->assertEquals('Minimal Warehouse', $warehouse->{WarehouseColumns::NAME});
    }
}
