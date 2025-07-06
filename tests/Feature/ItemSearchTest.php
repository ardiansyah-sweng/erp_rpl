<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemSearchTest extends TestCase
{
    // Catatan: Tidak pakai RefreshDatabase karena kamu ingin gunakan data yang sudah ada

    /** @test */
    public function it_can_find_items_by_keyword()
    {
        // Asumsi data 'Kaos Polos' sudah ada di tabel items
        $response = $this->get('/item/search/Kaos');

        $response->assertStatus(200);
        $response->assertSee('Kaos'); // pastikan nama item atau bagian dari nama muncul
    }

    /** @test */
    public function it_returns_error_if_no_item_found()
    {
        $response = $this->get('/item/search/ItemTidakAdaSamaSekali');

        $response->assertRedirect(); // redirect back karena tidak ditemukan
        $response->assertSessionHas('error'); // pastikan ada flash error
    }
}
