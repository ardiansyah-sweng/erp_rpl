<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseGetAllFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getWareHouseAll method through HTTP request
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_through_api_endpoint()
    {
        // Arrange - Create test data
        $testData = [
            'warehouse_name' => 'API Test Warehouse',
            'warehouse_address' => 'API Test Address',
            'warehouse_telephone' => '081234567894',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true
        ];

        Warehouse::addWarehouse($testData);

        // Note: Jika ada endpoint API untuk getWareHouseAll, test ini bisa digunakan
        // Contoh jika ada route: GET /api/warehouses
        // $response = $this->getJson('/api/warehouses');
        // $response->assertStatus(200);
        // $response->assertJsonCount(1);
        
        // Untuk saat ini, kita test langsung method-nya
        $warehouse = new Warehouse();
        $result = $warehouse->getWareHouseAll();
        
        $this->assertCount(1, $result);
        $this->assertEquals('API Test Warehouse', $result->first()->warehouse_name);
    }

    /**
     * Test performance of getWareHouseAll with large dataset
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_performance_with_large_dataset()
    {
        // Arrange - Create multiple warehouses
        $warehouseCount = 100;
        
        for ($i = 1; $i <= $warehouseCount; $i++) {
            $testData = [
                'warehouse_name' => "Performance Test Warehouse {$i}",
                'warehouse_address' => "Performance Test Address {$i}",
                'warehouse_telephone' => "08123456789{$i}",
                'is_rm_whouse' => ($i % 2 == 0),
                'is_fg_whouse' => ($i % 3 == 0),
                'is_active' => true
            ];
            Warehouse::addWarehouse($testData);
        }

        $warehouse = new Warehouse();

        // Act - Measure execution time
        $startTime = microtime(true);
        $result = $warehouse->getWareHouseAll();
        $endTime = microtime(true);
        
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertCount($warehouseCount, $result);
        $this->assertLessThan(1.0, $executionTime, 'Method should execute in less than 1 second');
        
        // Check that all items are properly loaded
        $result->each(function ($item) {
            $this->assertInstanceOf(Warehouse::class, $item);
            $this->assertNotNull($item->warehouse_name);
        });
    }

    /**
     * Test memory usage of getWareHouseAll
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_memory_usage()
    {
        // Arrange - Create test data
        for ($i = 1; $i <= 50; $i++) {
            $testData = [
                'warehouse_name' => "Memory Test Warehouse {$i}",
                'warehouse_address' => "Memory Test Address {$i}",
                'warehouse_telephone' => "08123456789{$i}",
                'is_rm_whouse' => true,
                'is_fg_whouse' => false,
                'is_active' => true
            ];
            Warehouse::addWarehouse($testData);
        }

        $warehouse = new Warehouse();

        // Act - Measure memory usage
        $memoryBefore = memory_get_usage();
        $result = $warehouse->getWareHouseAll();
        $memoryAfter = memory_get_usage();
        
        $memoryUsed = $memoryAfter - $memoryBefore;

        // Assert
        $this->assertCount(50, $result);
        $this->assertLessThan(5 * 1024 * 1024, $memoryUsed, 'Memory usage should be less than 5MB'); // 5MB limit
    }

    /**
     * Test consistency of getWareHouseAll results
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_consistency()
    {
        // Arrange - Create test data
        $testData = [
            'warehouse_name' => 'Consistency Test Warehouse',
            'warehouse_address' => 'Consistency Test Address',
            'warehouse_telephone' => '081234567895',
            'is_rm_whouse' => true,
            'is_fg_whouse' => true,
            'is_active' => true
        ];

        Warehouse::addWarehouse($testData);
        $warehouse = new Warehouse();

        // Act - Call method multiple times
        $result1 = $warehouse->getWareHouseAll();
        $result2 = $warehouse->getWareHouseAll();
        $result3 = $warehouse->getWareHouseAll();

        // Assert - Results should be consistent
        $this->assertEquals($result1->count(), $result2->count());
        $this->assertEquals($result2->count(), $result3->count());
        
        $this->assertEquals($result1->toArray(), $result2->toArray());
        $this->assertEquals($result2->toArray(), $result3->toArray());
    }

    /**
     * Test getWareHouseAll with database transaction rollback
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_get_warehouse_all_with_transaction_rollback()
    {
        // Arrange - Create initial data
        $testData = [
            'warehouse_name' => 'Transaction Test Warehouse',
            'warehouse_address' => 'Transaction Test Address',
            'warehouse_telephone' => '081234567896',
            'is_rm_whouse' => false,
            'is_fg_whouse' => true,
            'is_active' => true
        ];

        Warehouse::addWarehouse($testData);
        $warehouse = new Warehouse();
        
        // Check initial count
        $initialResult = $warehouse->getWareHouseAll();
        $initialCount = $initialResult->count();

        // Act - Simulate transaction that gets rolled back
        \DB::beginTransaction();
        
        // Add more data within transaction
        $additionalData = [
            'warehouse_name' => 'Transaction Test Warehouse 2',
            'warehouse_address' => 'Transaction Test Address 2',
            'warehouse_telephone' => '081234567897',
            'is_rm_whouse' => true,
            'is_fg_whouse' => false,
            'is_active' => true
        ];
        Warehouse::addWarehouse($additionalData);
        
        // Check count within transaction
        $transactionResult = $warehouse->getWareHouseAll();
        $transactionCount = $transactionResult->count();
        
        // Rollback transaction
        \DB::rollback();
        
        // Check count after rollback
        $finalResult = $warehouse->getWareHouseAll();
        $finalCount = $finalResult->count();

        // Assert
        $this->assertEquals($initialCount + 1, $transactionCount);
        $this->assertEquals($initialCount, $finalCount);
        $this->assertEquals($initialResult->toArray(), $finalResult->toArray());
    }
}
