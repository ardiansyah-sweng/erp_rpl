<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Warehouse;

class WarehouseDeleteTest extends TestCase
{
    public function test_delete_warehouse_successfully()
    {
        // Create a warehouse
        $warehouse = Warehouse::factory()->create();
        $warehouseId = $warehouse->id;

        // Delete the warehouse
        $warehouseModel = new Warehouse();
        $result = $warehouseModel->deleteWarehouse($warehouseId);

        // Assert the deletion was successful
        $this->assertTrue($result);
        
        // Assert the warehouse no longer exists in database
        $this->assertNull(Warehouse::find($warehouseId));
    }

    public function test_delete_warehouse_returns_false_when_not_found()
    {
        // Try to delete non-existent warehouse
        $warehouseModel = new Warehouse();
        $result = $warehouseModel->deleteWarehouse(99999);

        // Assert it returns false
        $this->assertFalse($result);
    }

    public function test_delete_warehouse_with_valid_id()
    {
        // Create a warehouse
        $warehouse = Warehouse::factory()->create([
            'warehouse_name' => 'Test Warehouse Delete',
            'warehouse_address' => 'Test Address',
            'is_active' => 1
        ]);

        $warehouseId = $warehouse->id;

        // Verify warehouse exists before deletion
        $existingWarehouse = Warehouse::find($warehouseId);
        $this->assertNotNull($existingWarehouse);
        $this->assertEquals('Test Warehouse Delete', $existingWarehouse->warehouse_name);

        // Delete the warehouse
        $warehouseModel = new Warehouse();
        $result = $warehouseModel->deleteWarehouse($warehouseId);

        // Assert the deletion was successful
        $this->assertTrue($result);
        
        // Verify warehouse doesn't exist after deletion
        $deletedWarehouse = Warehouse::find($warehouseId);
        $this->assertNull($deletedWarehouse);
    }
}
