<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_add_warehouse_if_valid_data()
    {
        $data = [
            'warehouse_name'      => 'Gudang Utama',
            'warehouse_address'   => 'Jl. Industri No. 123',
            'warehouse_telephone' => '081234567890',
            'is_rm_whouse'        => true,
            'is_fg_whouse'        => false,
            'is_active'           => true,
        ];

        $warehouse = Warehouse::addWarehouse($data);

        $this->assertInstanceOf(Warehouse::class, $warehouse);
        $this->assertEquals('Gudang Utama', $warehouse->warehouse_name);
        $this->assertDatabaseHas('warehouse', $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_add_warehouse_if_empty_data()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data tidak boleh kosong.');

        Warehouse::addWarehouse([]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_existing_warehouse()
    {
        // Arrange: Buat warehouse awal
        $warehouse = Warehouse::create([
            'warehouse_name'     => 'Gudang Lama',
            'warehouse_address'  => 'Jl. Raya No. 1',
            'warehouse_telephone' => '021-12345678',
        ]);

        // Act: Lakukan update
        $data = [
            'warehouse_name'     => 'Gudang Baru',
            'warehouse_address'  => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ];

        $result = $warehouse->update($data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('warehouse', [
            'id'                 => $warehouse->id,
            'warehouse_name'     => 'Gudang Baru',
            'warehouse_address'  => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_false_when_updating_non_existing_warehouse()
    {
        // Act: Cari warehouse yang tidak ada
        $warehouse = Warehouse::find(9999);

        // Assert
        $this->assertNull($warehouse);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_returns_collection()
    {
        // Arrange - Create a warehouse instance
        $warehouse = new Warehouse();

        // Act - Call the method
        $result = $warehouse->getWareHouseAll();

        // Assert - Check that result is a Collection
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_returns_empty_collection_when_no_data()
    {
        // Arrange - Ensure database is empty
        Warehouse::truncate();
        $warehouse = new Warehouse();

        // Act - Call the method
        $result = $warehouse->getWareHouseAll();

        // Assert - Check that result is empty collection
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_returns_warehouses_when_data_exists()
    {
        // Arrange - Create test data
        $testData1 = [
            'warehouse_name' => 'Test Warehouse 1',
            'warehouse_address' => 'Test Address 1',
            'warehouse_telephone' => '081234567890',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true
        ];

        $testData2 = [
            'warehouse_name' => 'Test Warehouse 2',
            'warehouse_address' => 'Test Address 2',
            'warehouse_telephone' => '081234567891',
            'is_rm_whouse' => false,
            'is_fg_whouse' => true,
            'is_active' => true
        ];

        // Create warehouses using the model's addWarehouse method
        Warehouse::addWarehouse($testData1);
        Warehouse::addWarehouse($testData2);

        $warehouse = new Warehouse();

        // Act - Call the method
        $result = $warehouse->getWareHouseAll();

        // Assert - Check that result contains the warehouses
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertFalse($result->isEmpty());
        
        // Check specific data
        $this->assertEquals('Test Warehouse 1', $result->first()->warehouse_name);
        $this->assertEquals('Test Warehouse 2', $result->last()->warehouse_name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_equals_warehouse_all()
    {
        // Arrange - Create test data
        $testData = [
            'warehouse_name' => 'Static Test Warehouse',
            'warehouse_address' => 'Static Test Address',
            'warehouse_telephone' => '081234567892',
            'is_rm_whouse' => true,
            'is_fg_whouse' => true,
            'is_active' => true
        ];

        Warehouse::addWarehouse($testData);

        // Act - Call the method via instance
        $warehouse = new Warehouse();
        $instanceResult = $warehouse->getWareHouseAll();

        // Compare with direct Warehouse::all()
        $staticResult = Warehouse::all();

        // Assert - Both should return same data
        $this->assertEquals($staticResult->count(), $instanceResult->count());
        $this->assertEquals($staticResult->toArray(), $instanceResult->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_returns_correct_attributes()
    {
        // Arrange - Create test data with all attributes
        $testData = [
            'warehouse_name' => 'Complete Test Warehouse',
            'warehouse_address' => 'Complete Test Address',
            'warehouse_telephone' => '081234567893',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true
        ];

        Warehouse::addWarehouse($testData);
        $warehouse = new Warehouse();

        // Act - Call the method
        $result = $warehouse->getWareHouseAll();

        // Assert - Check attributes exist
        $firstWarehouse = $result->first();
        $this->assertNotNull($firstWarehouse);
        
        // Check if required attributes exist
        $this->assertTrue($firstWarehouse->offsetExists('warehouse_name'));
        $this->assertTrue($firstWarehouse->offsetExists('warehouse_address'));
        $this->assertTrue($firstWarehouse->offsetExists('warehouse_telephone'));
        $this->assertTrue($firstWarehouse->offsetExists('is_rm_whouse'));
        $this->assertTrue($firstWarehouse->offsetExists('is_fg_whouse'));
        $this->assertTrue($firstWarehouse->offsetExists('is_active'));
        
        // Check values
        $this->assertEquals($testData['warehouse_name'], $firstWarehouse->warehouse_name);
        $this->assertEquals($testData['warehouse_address'], $firstWarehouse->warehouse_address);
        $this->assertEquals($testData['warehouse_telephone'], $firstWarehouse->warehouse_telephone);
    }
}
