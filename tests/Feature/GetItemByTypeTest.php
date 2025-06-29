<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class GetItemByTypeTest extends TestCase
{
    // JANGAN pakai use RefreshDatabase; jika tidak mau reset isi tabel!

    public function test_can_get_items_by_valid_product_type()
    {
        // PASTIKAN ini product_id dan product_type sudah ADA di tabel products
        $product_id   = 'P001';  // contoh product_id yang sudah ada
        $product_type = 'RM';    // tipe produk sesuai product_id di atas

        // Insert ke tabel item
        $item = Item::create([
            'product_id'       => $product_id,
            'sku'              => 'SKU001',
            'item_name'        => 'Barang Uji',
            'measurement_unit' => 1,
            'avg_base_price'   => 0,
            'selling_price'    => 0,
            'purchase_unit'    => 0,
            'sell_unit'        => 0,
            'stock_unit'       => 0,
        ]);

        // Test endpoint
        $response = $this->get('/items/type/' . $product_type);
        $response->assertStatus(200);
        $response->assertJsonFragment(['item_name' => $item->item_name]);
        $response->assertJsonFragment(['product_type' => $product_type]);
    }
    
}