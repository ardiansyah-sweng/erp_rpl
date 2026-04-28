<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetItemByType extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji logika getItemByType dengan join tabel products dan items berdasarkan type.
     */
    public function test_get_item_by_type_with_product_join(): void
    {
        // 1. Setup data product dengan type tertentu
        Product::create([
            'product_id'   => 'P001',
            'name'         => 'Baut Baja',
            'type'         => 'RM',
            'category'     => 1,
            'description'  => 'Baut berkualitas tinggi',
        ]);

        Product::create([
            'product_id'   => 'P002',
            'name'         => 'Cat Akrilik',
            'type'         => 'FG',
            'category'     => 2,
            'description'  => 'Cat finishing premium',
        ]);

        // 2. Setup data items yang link ke products
        Item::create([
            'product_id'    => 'P001',
            'sku'           => 'S1',
            'name'          => 'Baut M8',
            'measurement'   => 1,
            'base_price'    => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 1,
            'sell_unit'     => 1,
            'stock_unit'    => 1,
        ]);

        Item::create([
            'product_id'    => 'P001',
            'sku'           => 'S2',
            'name'          => 'Baut M10',
            'measurement'   => 1,
            'base_price'    => 12000,
            'selling_price' => 18000,
            'purchase_unit' => 1,
            'sell_unit'     => 1,
            'stock_unit'    => 1,
        ]);

        Item::create([
            'product_id'    => 'P002',
            'sku'           => 'S3',
            'name'          => 'Cat Putih',
            'measurement'   => 1,
            'base_price'    => 50000,
            'selling_price' => 75000,
            'purchase_unit' => 1,
            'sell_unit'     => 1,
            'stock_unit'    => 1,
        ]);

        // 3. Eksekusi fungsi Model dengan filter type RM (Raw Material)
        $rawMaterialItems = Item::getItemByType('RM');
        $finishedGoodItems = Item::getItemByType('FG');

        // 4. Verifikasi hasil Raw Material
        $this->assertCount(2, $rawMaterialItems);
        $this->assertEquals('S1', $rawMaterialItems[0]->sku);
        $this->assertEquals('S2', $rawMaterialItems[1]->sku);
        $this->assertEquals('RM', $rawMaterialItems[0]->product_type);

        // 5. Verifikasi hasil Finished Good
        $this->assertCount(1, $finishedGoodItems);
        $this->assertEquals('S3', $finishedGoodItems[0]->sku);
        $this->assertEquals('FG', $finishedGoodItems[0]->product_type);
    }
}