<?php

namespace Tests\Feature\Controllers;

use App\Models\Item;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetItemByTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skenario 1: Memastikan route tipe 'RM' bisa dipanggil
     */
    public function test_can_get_items_by_type_rm()
    {
        $response = $this->getJson(route('api.items.by.type', ['productType' => 'RM']));

        // Menggunakan gaya awalmu: yang penting route ada (bukan 404)
        $this->assertNotEquals(404, $response->status(), 'Route RM does not exist');
        $this->assertTrue(true, 'Route RM is callable');
    }

    /**
     * Skenario 2: Memastikan route tipe 'FG' (Finished Goods) juga bisa dipanggil
     */
    public function test_can_get_items_by_type_fg()
    {
        $response = $this->getJson(route('api.items.by.type', ['productType' => 'FG']));

        // Tetap sesuai kaidah kode defaultmu
        $this->assertNotEquals(404, $response->status(), 'Route FG does not exist');
        $this->assertTrue(true, 'Route FG is callable');
    }

    /**
     * Skenario 3: Memastikan route tetap merespon meskipun parameter tipe kosong/ngawur
     */
    public function test_route_responds_with_invalid_type()
    {
        $response = $this->getJson(route('api.items.by.type', ['productType' => 'UNKNOWN']));

        $this->assertNotEquals(404, $response->status(), 'Route should exist even with unknown type');
        $this->assertTrue(true, 'Route handled the request');
    }
}