<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetItemByType extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji logika getItemByType langsung di dalam Model Item.
     */
    public function test_get_item_by_type_logic(): void
    {
        // 1. Setup data dummy dengan field lengkap untuk menghindari QueryException
        Item::create([
            'product_id'    => 'P1',
            'sku'           => 'S1',
            'name'          => 'Baut Baja',
            'measurement'   => 1,
            'base_price'    => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 1,
            'sell_unit'     => 1,
            'stock_unit'    => 1,
        ]);

        // 2. Eksekusi fungsi Model
        $itemModel = new Item();
        $results = $itemModel->getItem();

        // 3. Verifikasi hasil
        $this->assertCount(1, $results);
        $this->assertEquals('S1', $results->first()->sku);
    }
}