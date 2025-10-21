<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected $itemModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Pakai SQLite in-memory
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        // Buat table dummy sesuai nama tabel di model Item.php
        Schema::create('item', function (Blueprint $table) {
            $table->id(); // primary key tunggal
            $table->integer('product_id');
            $table->string('sku', 50);
            $table->string('item_name', 50);
            $table->integer('measurement_unit');
            $table->integer('avg_base_price');
            $table->integer('selling_price');
            $table->string('purchase_unit', 20);
            $table->string('sell_unit', 20);
            $table->string('stock_unit', 20);
            $table->timestamps();
        });

        $this->itemModel = new Item();
    }

    /** @test */
    public function get_item_returns_all_items()
    {
        // Arrange
        Item::factory()->create(['item_name' => 'Item A']);
        Item::factory()->create(['item_name' => 'Item B']);

        // Act
        $result = $this->itemModel->getItem();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals('Item A', $result[0]->item_name);
    }

    /** @test */
    public function get_item_returns_empty_collection_when_no_data()
    {
        // Act
        $result = $this->itemModel->getItem();

        // Assert
        $this->assertTrue($result->isEmpty());
    }

    /** @test */
    public function get_item_does_not_throw_error_when_table_empty()
    {
        $this->assertNotNull($this->itemModel->getItem());
    }
}
