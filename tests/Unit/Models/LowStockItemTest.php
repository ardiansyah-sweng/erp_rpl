<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\Product;
use App\Constants\ItemColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

class LowStockItemTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Item dengan stock_unit <= minimum_stock harus terdeteksi sebagai low stock.
     */
    public function test_item_below_minimum_stock_is_detected()
    {
        Item::factory()->create([
            'sku'           => 'LOW-001',
            'name'          => 'Item Stok Rendah',
            'stock_unit'    => 5,
            'minimum_stock' => 10,
        ]);

        $lowStockItems = Item::getLowStockItems();

        $this->assertCount(1, $lowStockItems);
        $this->assertEquals('LOW-001', $lowStockItems->first()->sku);
    }

    /**
     * Item dengan stock_unit == minimum_stock (tepat sama) juga harus terdeteksi.
     */
    public function test_item_equal_to_minimum_stock_is_detected()
    {
        Item::factory()->create([
            'sku'           => 'EQ-001',
            'name'          => 'Item Stok Sama',
            'stock_unit'    => 10,
            'minimum_stock' => 10,
        ]);

        $lowStockItems = Item::getLowStockItems();

        $this->assertCount(1, $lowStockItems);
        $this->assertEquals('EQ-001', $lowStockItems->first()->sku);
    }

    /**
     * Item dengan stock_unit > minimum_stock TIDAK boleh muncul.
     */
    public function test_item_above_minimum_stock_is_not_detected()
    {
        Item::factory()->create([
            'sku'           => 'OK-001',
            'name'          => 'Item Stok Aman',
            'stock_unit'    => 50,
            'minimum_stock' => 10,
        ]);

        $lowStockItems = Item::getLowStockItems();

        $this->assertCount(0, $lowStockItems);
    }

    /**
     * countLowStockItems harus mengembalikan jumlah yang benar.
     */
    public function test_count_low_stock_items_returns_correct_count()
    {
        Item::factory()->create(['sku' => 'L1', 'stock_unit' => 2, 'minimum_stock' => 5]);
        Item::factory()->create(['sku' => 'L2', 'stock_unit' => 0, 'minimum_stock' => 5]);
        Item::factory()->create(['sku' => 'OK', 'stock_unit' => 100, 'minimum_stock' => 5]);

        $count = Item::countLowStockItems();

        $this->assertEquals(2, $count);
    }

    /**
     * Jika tidak ada item low stock, getLowStockItems harus mengembalikan collection kosong.
     */
    public function test_get_low_stock_items_returns_empty_when_all_stock_is_sufficient()
    {
        Item::factory()->create(['sku' => 'A1', 'stock_unit' => 100, 'minimum_stock' => 10]);
        Item::factory()->create(['sku' => 'A2', 'stock_unit' => 50,  'minimum_stock' => 5]);

        $lowStockItems = Item::getLowStockItems();

        $this->assertTrue($lowStockItems->isEmpty());
    }

    /**
     * Item dengan minimum_stock = 0 dan stock_unit = 0 harus terdeteksi.
     */
    public function test_item_with_zero_stock_and_zero_minimum_is_detected()
    {
        Item::factory()->create([
            'sku'           => 'ZERO-001',
            'stock_unit'    => 0,
            'minimum_stock' => 0,
        ]);

        $lowStockItems = Item::getLowStockItems();

        $this->assertCount(1, $lowStockItems);
    }
}
