<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_item_successfully()
    {
        // 1. Buat data manual (karena modelmu tidak punya factory)
        $item = Item::create([
            'product_id' => 1,
            'sku' => 'TEST-SKU',
            'item_name' => 'Barang Uji Coba',
            'measurement_unit' => 1,
            'avg_base_price' => 100,
            'selling_price' => 150,
            'purchase_unit' => 1,
            'sell_unit' => 1,
            'stock_unit' => 10
        ]);

        // 2. Aksi hapus (sesuai route web.php kamu)
        $response = $this->delete("/item/{$item->id}");

        // 3. Cek apakah di tabel 'item' sudah hilang
        $this->assertDatabaseMissing('item', [
            'id' => $item->id
        ]);

        $response->assertStatus(302);
    }
}