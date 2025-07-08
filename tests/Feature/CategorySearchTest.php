<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategorySearchTest extends TestCase
{
    /** @test */
    public function it_can_search_categories_by_name()
    {
        $response = $this->get('/category/search?q=Mainan');

        $response->assertStatus(200);
        $response->assertSeeText('Mainan & Permainan');
        $response->assertDontSeeText('Industri & Ilmiah');
    }

    /** @test */
    public function it_returns_all_categories_if_query_is_empty()
    {
        $response = $this->get('/category/search');

        $response->assertStatus(200);
        $response->assertSeeText('Produk Buatan Tangan');
        $response->assertSeeText('Perkakas & Perbaikan Rumah');
        $response->assertSeeText('Olahraga & Aktivitas Luar Ruangan');
    }

    /** @test */
    public function it_shows_nothing_when_nothing_matches()
    {
        $response = $this->get('/category/search?q=XYZ123TidakAda');

        $response->assertStatus(200);
        $response->assertDontSeeText('Produk Buatan Tangan');
        $response->assertDontSeeText('Mainan');
        $response->assertDontSeeText('Elektronik');
    }
}
