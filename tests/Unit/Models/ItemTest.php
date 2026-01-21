<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\PurchaseOrderDetail;
use App\Models\Product;
use App\Models\MeasurementUnit;
use App\Constants\Messages;
use Exception;
use Tests\TestCase as BaseTestCase;

class ItemTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Reset auto-increment untuk memastikan ID dimulai dari 1
        \DB::statement('ALTER TABLE items AUTO_INCREMENT = 1');
    }

    // ========== DELETE ITEM BY ID METHOD TESTS ==========

    /**
     * Test deleteItemById method successfully deletes an item
     */
    public function test_it_successfully_deletes_item_when_no_relations_exist()
    {
        // Arrange - Create test item
        $item = Item::factory()->create([
            'sku' => 'TEST-001',
            'name' => 'Test Item for Deletion',
            'product_id' => 'PROD',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100
        ]);

        $itemId = $item->id;

        // Act - Delete the item
        $result = Item::deleteItemById($itemId);

        // Assert - Item should be deleted successfully
        $this->assertTrue($result);
        $this->assertDatabaseMissing('items', ['id' => $itemId]);
        $this->assertNull(Item::find($itemId));
    }

    /**
     * Test deleteItemById method returns false when item does not exist
     */
    public function test_it_returns_false_when_item_does_not_exist()
    {
        // Arrange - Use non-existent item ID
        $nonExistentId = 99999;

        // Act - Try to delete non-existent item
        $result = Item::deleteItemById($nonExistentId);

        // Assert - Should return false
        $this->assertFalse($result);
    }

    /**
     * Test deleteItemById method throws exception when item has purchase order relations
     */
    public function test_it_throws_exception_when_item_has_purchase_order_relations()
    {
        // Arrange - Create item with purchase order relation
        $item = Item::factory()->create([
            'sku' => 'TEST-002',
            'name' => 'Test Item with Relations',
            'product_id' => 'PROD',
            'measurement' => 1
        ]);

        // Create purchase order detail that references this item
        PurchaseOrderDetail::factory()->create([
            'product_id' => $item->sku, // PO detail uses SKU as product_id
            'po_number' => 'PO-001',
            'base_price' => 10000,
            'quantity' => 5,
            'amount' => 50000
        ]);

        // Act & Assert - Should throw exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Messages::ITEM_IN_USE);

        Item::deleteItemById($item->id);

        // Assert - Item should still exist
        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }

    /**
     * Test deleteItemById method decrements IDs of subsequent items
     */
    public function test_it_decrements_subsequent_item_ids_after_deletion()
    {
        // Arrange - Create multiple items with sequential IDs
        $item1 = Item::factory()->create([
            'sku' => 'TEST-001',
            'name' => 'Item 1'
        ]);
        
        $item2 = Item::factory()->create([
            'sku' => 'TEST-002', 
            'name' => 'Item 2'
        ]);
        
        $item3 = Item::factory()->create([
            'sku' => 'TEST-003',
            'name' => 'Item 3'
        ]);

        $originalItem2Id = $item2->id;
        $originalItem3Id = $item3->id;

        // Act - Delete the first item
        $result = Item::deleteItemById($item1->id);

        // Assert - Deletion successful
        $this->assertTrue($result);
        
        // Assert - Item 1 (by SKU) should be deleted
        $this->assertDatabaseMissing('items', ['sku' => 'TEST-001']);
        
        // Assert - Only 2 items remain
        $this->assertEquals(2, Item::count());

        // Assert - Subsequent items should have decremented IDs
        // Query items by SKU instead of refresh (because ID has changed)
        $updatedItem2 = Item::where('sku', 'TEST-002')->first();
        $updatedItem3 = Item::where('sku', 'TEST-003')->first();
        
        // Assert - IDs decremented by 1 (relative to original)
        $this->assertEquals($originalItem2Id - 1, $updatedItem2->id);
        $this->assertEquals($originalItem3Id - 1, $updatedItem3->id);
        
        // Assert - Item 2's new ID is 1 less than Item 3's new ID
        $this->assertEquals($updatedItem2->id + 1, $updatedItem3->id);
        
        // Assert - Proper SKU mapping to new IDs
        $this->assertDatabaseHas('items', [
            'id' => $updatedItem2->id, 
            'sku' => 'TEST-002'
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $updatedItem3->id, 
            'sku' => 'TEST-003'
        ]);
    }

    /**
     * Test deleteItemById method handles item with zero ID gracefully
     */
    public function test_it_handles_zero_id_gracefully()
    {
        // Arrange - Use ID zero
        $zeroId = 0;

        // Act - Try to delete item with ID zero
        $result = Item::deleteItemById($zeroId);

        // Assert - Should return false (no item found)
        $this->assertFalse($result);
    }

    /**
     * Test deleteItemById method handles negative ID gracefully
     */
    public function test_it_handles_negative_id_gracefully()
    {
        // Arrange - Use negative ID
        $negativeId = -1;

        // Act - Try to delete item with negative ID
        $result = Item::deleteItemById($negativeId);

        // Assert - Should return false (no item found)
        $this->assertFalse($result);
    }

    /**
     * Test deleteItemById method with item that has no subsequent items
     */
    public function test_it_deletes_last_item_without_affecting_others()
    {
        // Arrange - Create items where we'll delete the last one
        $item1 = Item::factory()->create([
            'sku' => 'TEST-001',
            'name' => 'Item 1'
        ]);
        
        $item2 = Item::factory()->create([
            'sku' => 'TEST-002',
            'name' => 'Item 2 (to be deleted)'
        ]);

        $originalItem1Id = $item1->id;

        // Act - Delete the last item
        $result = Item::deleteItemById($item2->id);

        // Assert - Deletion successful
        $this->assertTrue($result);
        $this->assertDatabaseMissing('items', ['id' => $item2->id]);

        // Assert - Previous item should remain unchanged
        $item1->refresh();
        $this->assertEquals($originalItem1Id, $item1->id);
        $this->assertDatabaseHas('items', [
            'id' => $originalItem1Id,
            'sku' => 'TEST-001'
        ]);
    }

    /**
     * Test deleteItemById method preserves item data integrity after deletion
     */
    public function test_it_preserves_data_integrity_after_deletion()
    {
        // Arrange - Create test items with specific data
        $item1 = Item::factory()->create([
            'sku' => 'PRESERVE-001',
            'name' => 'Item to Delete',
            'base_price' => 5000
        ]);

        $item2 = Item::factory()->create([
            'sku' => 'PRESERVE-002',
            'name' => 'Item to Keep',
            'base_price' => 10000
        ]);

        // Act - Delete first item
        $result = Item::deleteItemById($item1->id);

        // Assert - Deletion successful and data integrity preserved
        $this->assertTrue($result);

        // Check remaining item data integrity
        $remainingItem = Item::where('sku', 'PRESERVE-002')->first();
        $this->assertNotNull($remainingItem);
        $this->assertEquals('Item to Keep', $remainingItem->name);
        $this->assertEquals(10000, $remainingItem->base_price);
        $this->assertEquals('PRESERVE-002', $remainingItem->sku);
    }


    /**
     * Test addItem method successfully creates and returns a new item
     */
    public function test_addItem_successfully_creates_and_returns_item()
    {
        // Arrange - Prepare item data (product_id must be exactly 4 characters)
        $itemData = [
            'sku' => 'ADD-001',
            'name' => 'Test Item for Add',
            'product_id' => 'PROD',
            'measurement' => 1,
            'base_price' => 20000,
            'selling_price' => 25000,
            'purchase_unit' => 50,
            'sell_unit' => 50,
            'stock_unit' => 200
        ];

        // Act - Create new item using addItem method
        $item = new Item();
        $createdItem = $item->addItem($itemData);

        // Assert - Item should be created and returned
        $this->assertInstanceOf(Item::class, $createdItem);
        $this->assertEquals('ADD-001', $createdItem->sku);
        $this->assertEquals('Test Item for Add', $createdItem->name);
        $this->assertEquals('PROD', $createdItem->product_id);
        $this->assertEquals(20000, $createdItem->base_price);
        $this->assertEquals(25000, $createdItem->selling_price);

        // Assert - Item exists in database
        $this->assertDatabaseHas('items', [
            'sku' => 'ADD-001',
            'name' => 'Test Item for Add',
            'product_id' => 'PROD'
        ]);
    }

    /**
     * Test addItem method creates item with minimal required data
     */
    public function test_addItem_creates_item_with_minimal_data()
    {
        // Arrange - Minimal item data (product_id and measurement are required)
        $minimalData = [
            'sku' => 'MIN-001',
            'name' => 'Minimal Item',
            'product_id' => 'PROD',
            'measurement' => 1
        ];

        // Act - Create item
        $item = new Item();
        $createdItem = $item->addItem($minimalData);

        // Assert - Item created successfully
        $this->assertInstanceOf(Item::class, $createdItem);
        $this->assertEquals('MIN-001', $createdItem->sku);
        $this->assertEquals('Minimal Item', $createdItem->name);
        $this->assertEquals('PROD', $createdItem->product_id);
        $this->assertEquals(1, $createdItem->measurement);

        // Assert - Exists in database
        $this->assertDatabaseHas('items', [
            'sku' => 'MIN-001',
            'name' => 'Minimal Item',
            'product_id' => 'PROD',
            'measurement' => 1
        ]);
    }
}
