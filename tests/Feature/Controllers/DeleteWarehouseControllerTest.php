<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\Messages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteWarehouseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        config(['db_tables.warehouse' => 'warehouses']);
    }

    /**
     * Test: Successfully delete a warehouse that has no dependencies (Web Request).
     
     */
    public function test_it_can_delete_an_unused_warehouse_web_request()
    {
        // Arrange: Create a warehouse that is not used
        $warehouse = Warehouse::factory()->create([
            'warehouse_name' => 'Warehouse to Delete',
        ]);

        // Act: Send a DELETE request via the deleteWarehouse method
        $response = $this->delete(route('warehouses.destroy', $warehouse->id));

        // Assert: Check for successful redirect and warehouse deletion
        $response->assertRedirect(route('warehouses.index'));
        $response->assertSessionHas('success', Messages::WAREHOUSE_DELETED);
        
        // Verify warehouse is deleted from database
        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Successfully delete a warehouse (API Request).
     
     */
    public function test_it_can_delete_an_unused_warehouse_api_request()
    {
        // Arrange: Create a warehouse
        $warehouse = Warehouse::factory()->create([
            'warehouse_name' => 'Warehouse API Delete',
        ]);

        // Act: Send a DELETE request with JSON header
        $response = $this->deleteJson(route('api.warehouses.destroy', $warehouse->id));

        // Assert: Check for successful JSON response
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => Messages::WAREHOUSE_DELETED,
                 ]);

        // Verify warehouse is deleted from database
        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Attempt to delete a non-existent warehouse (Web Request).
     
     */
    public function test_it_returns_error_when_deleting_non_existent_warehouse_web()
    {
        // Arrange: Non-existent ID
        $nonExistentId = 9999;

        // Act: Attempt to delete
        $response = $this->delete(route('warehouses.destroy', $nonExistentId));

        // Assert: Should redirect with error message
        $response->assertRedirect(route('warehouses.index'));
        $response->assertSessionHas('error', Messages::WAREHOUSE_NOT_FOUND);
    }

    /**
     * Test: Attempt to delete a non-existent warehouse (API Request).
    
     */
    public function test_it_returns_error_when_deleting_non_existent_warehouse_api()
    {
        // Arrange: Non-existent ID
        $nonExistentId = 9999;

        // Act: Attempt to delete via API
        $response = $this->deleteJson(route('api.warehouses.destroy', $nonExistentId));

        // Assert: Should return 404 JSON response
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::WAREHOUSE_NOT_FOUND,
                 ]);
    }

    /**
     * Test: Cannot delete warehouse when stock records exist (Web Request).
    
     */
    public function test_it_cannot_delete_warehouse_with_stock_records_web()
    {
        // Arrange: Create warehouse and stock record
        $warehouse = Warehouse::factory()->create();
        
        // Create stock table if it doesn't exist
        if (!Schema::hasTable('stock')) {
            DB::statement('CREATE TABLE stock (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                warehouse_id BIGINT UNSIGNED,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');
        }
        
        DB::table('stock')->insert([
            'warehouse_id' => $warehouse->id,
        ]);

        // Act: Try to delete
        $response = $this->delete(route('warehouses.destroy', $warehouse->id));

        // Assert: Should have error message
        $response->assertRedirect(route('warehouses.index'));
        $response->assertSessionHas('error', Messages::WAREHOUSE_IN_USE);
        
        // Verify warehouse still exists
        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Cannot delete warehouse when stock records exist (API Request).
    
     */
    public function test_it_cannot_delete_warehouse_with_stock_records_api()
    {
        // Arrange: Create warehouse and stock record
        $warehouse = Warehouse::factory()->create();
        
        // Create stock table if it doesn't exist
        if (!Schema::hasTable('stock')) {
            DB::statement('CREATE TABLE stock (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                warehouse_id BIGINT UNSIGNED,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');
        }
        
        DB::table('stock')->insert([
            'warehouse_id' => $warehouse->id,
        ]);

        // Act: Try to delete via API
        $response = $this->deleteJson(route('api.warehouses.destroy', $warehouse->id));

        // Assert: Should return 422 with error
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::WAREHOUSE_IN_USE,
                 ]);

        // Verify warehouse still exists
        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Cannot delete warehouse when material inventory records exist (Web Request).
     
     */
    public function test_it_cannot_delete_warehouse_with_inventory_records_web()
    {
        // Arrange: Create warehouse and inventory record
        $warehouse = Warehouse::factory()->create();
        
        // Create material_inventory table if it doesn't exist
        if (!Schema::hasTable('material_inventory')) {
            DB::statement('CREATE TABLE material_inventory (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                warehouse_id BIGINT UNSIGNED,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');
        }
        
        DB::table('material_inventory')->insert([
            'warehouse_id' => $warehouse->id,
        ]);

        // Act: Try to delete
        $response = $this->delete(route('warehouses.destroy', $warehouse->id));

        // Assert: Should have error message
        $response->assertRedirect(route('warehouses.index'));
        $response->assertSessionHas('error', Messages::WAREHOUSE_IN_USE);
        
        // Verify warehouse still exists
        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Cannot delete warehouse when material inventory records exist (API Request).
     
     */
    public function test_it_cannot_delete_warehouse_with_inventory_records_api()
    {
        // Arrange: Create warehouse and inventory record
        $warehouse = Warehouse::factory()->create();
        
        // Create material_inventory table if it doesn't exist
        if (!Schema::hasTable('material_inventory')) {
            DB::statement('CREATE TABLE material_inventory (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                warehouse_id BIGINT UNSIGNED,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');
        }
        
        DB::table('material_inventory')->insert([
            'warehouse_id' => $warehouse->id,
        ]);

        // Act: Try to delete via API
        $response = $this->deleteJson(route('api.warehouses.destroy', $warehouse->id));

        // Assert: Should return 422 with error
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => Messages::WAREHOUSE_IN_USE,
                 ]);

        // Verify warehouse still exists
        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Can delete warehouse via deleteWarehouse deprecated method.
     
     */
    public function test_delete_warehouse_deprecated_method_delegates_to_destroy()
    {
        // Arrange: Create a warehouse
        $warehouse = Warehouse::factory()->create();

        // Act: Call the deprecated deleteWarehouse method
        $response = $this->delete(route('warehouses.destroy', $warehouse->id));

        // Assert: Should successfully delete
        $response->assertRedirect(route('warehouses.index'));
        $response->assertSessionHas('success', Messages::WAREHOUSE_DELETED);
        
        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    /**
     * Test: Delete warehouse with specific properties.
     
     */
    public function test_it_can_delete_warehouse_with_properties()
    {
        // Arrange: Create warehouse with specific properties
        $warehouse = Warehouse::factory()->create([
            'warehouse_name' => 'Special Warehouse',
            'warehouse_address' => '123 Main St',
            'warehouse_phone' => '555-1234',
            'is_active' => true,
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => false,
        ]);

        // Act: Delete the warehouse
        $response = $this->delete(route('warehouses.destroy', $warehouse->id));

        // Assert: Verify deletion
        $response->assertRedirect(route('warehouses.index'));
        $response->assertSessionHas('success', Messages::WAREHOUSE_DELETED);
        
        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
            'warehouse_name' => 'Special Warehouse',
        ]);
    }

    /**
     * Test: Multiple warehouses can be deleted independently.
    
     */
    public function test_it_can_delete_multiple_warehouses_independently()
    {
        // Arrange: Create multiple warehouses
        $warehouse1 = Warehouse::factory()->create(['warehouse_name' => 'Warehouse 1']);
        $warehouse2 = Warehouse::factory()->create(['warehouse_name' => 'Warehouse 2']);
        $warehouse3 = Warehouse::factory()->create(['warehouse_name' => 'Warehouse 3']);

        // Act: Delete first warehouse
        $response1 = $this->delete(route('warehouses.destroy', $warehouse1->id));
        
        // Assert: First warehouse deleted
        $response1->assertRedirect(route('warehouses.index'));
        $response1->assertSessionHas('success', Messages::WAREHOUSE_DELETED);
        $this->assertDatabaseMissing('warehouses', ['id' => $warehouse1->id]);

        // Verify other warehouses still exist
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse2->id]);
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse3->id]);

        // Act: Delete second warehouse
        $response2 = $this->delete(route('warehouses.destroy', $warehouse2->id));

        // Assert: Second warehouse deleted
        $response2->assertRedirect(route('warehouses.index'));
        $this->assertDatabaseMissing('warehouses', ['id' => $warehouse2->id]);

        // Verify third warehouse still exists
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse3->id]);
    }
}
