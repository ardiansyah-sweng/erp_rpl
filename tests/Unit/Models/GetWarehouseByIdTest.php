<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;

class GetWarehouseByIdTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure migrations run so table exists
        $this->artisan('migrate');
    }

    /**
     * TC-WH-23: View warehouse detail (valid id) - should return full warehouse data
     */
    public function test_getWarehouseById_returns_full_warehouse_detail_for_valid_id()
    {
        // Arrange
        $warehouse = Warehouse::create([
            WarehouseColumns::NAME => 'Gudang TC23',
            WarehouseColumns::ADDRESS => 'Jl. Testing No.1',
            WarehouseColumns::PHONE => '021-9090909',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act
        $result = Warehouse::getWarehouseById($warehouse->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Warehouse::class, $result);
        $this->assertEquals($warehouse->id, $result->id);
        $this->assertEquals('Gudang TC23', $result->{WarehouseColumns::NAME});
        $this->assertEquals('Jl. Testing No.1', $result->{WarehouseColumns::ADDRESS});
        $this->assertEquals('021-9090909', $result->{WarehouseColumns::PHONE});
        $this->assertTrue((bool) $result->{WarehouseColumns::IS_ACTIVE});
    }

    /**
     * TC-WH-24: View non-existing warehouse - should return null
     */
    public function test_getWarehouseById_returns_null_for_nonexistent_id()
    {
        // Act
        $result = Warehouse::getWarehouseById(123456789);

        // Assert
        $this->assertNull($result);
    }
}
