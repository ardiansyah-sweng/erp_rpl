<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class GetItemByTypeTest extends TestCase
{
    public function test_can_get_items_by_valid_product_type()
    {
        // Ambil satu data item yang sudah ada dan join ke products
        $existingItem = Item::join('products', 'item.product_id', '=', 'products.product_id')
            ->where('products.product_type', 'RM') // GANTI 'RM' sesuai product_type yang pasti ADA di tabelmu
            ->select('item.*', 'products.product_type', 'products.product_name')
            ->first();

        // Pastikan memang ada datanya (jangan sampai test berjalan dengan tabel kosong)
        $this->assertNotNull($existingItem, 'Data item dengan product_type RM tidak ditemukan di database!');

        // Panggil endpoint
        $response = $this->get('/items/type/' . $existingItem->product_type);

        // Pastikan response OK dan hasilnya mengandung item yang diambil
        $response->assertStatus(200);
        $response->assertJsonFragment(['item_name' => $existingItem->item_name]);
        $response->assertJsonFragment(['product_type' => $existingItem->product_type]);
    }
}