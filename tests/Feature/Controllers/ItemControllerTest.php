<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;

class ItemControllerTest extends TestCase
{
    /** @test */
    public function it_can_show_item_detail_page()
    {
        // 1️⃣ Pastikan ada data di tabel items
        $item = Item::first();
        if (!$item) {
            $item = Item::create([
                'name' => 'Test Item',
                'sku' => 'SKU001',
                'price' => 10000
            ]);
        }

        // 2️⃣ Panggil route/controller yang menampilkan detail item
        $response = $this->get('/item/' . $item->id);

        // 3️⃣ Pastikan page berhasil dibuka
        $response->assertStatus(200);

        // 4️⃣ Pastikan view yang digunakan benar
        $response->assertViewIs('item.detail');

        // 5️⃣ Pastikan data item dikirim ke view
        $response->assertViewHas('item', function ($viewItem) use ($item) {
            return $viewItem->id === $item->id;
        });
    }
}
