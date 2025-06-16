<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    public function test_search_kaos()
    {
        $response = $this->get('/product/search?search=Kaos');
        $response->assertStatus(200);
        $response->assertSee('Kaos TShirt');
    }

    public function test_search_topi()
    {
        $response = $this->get('/product/search?search=Topi');
        $response->assertStatus(200);
        $response->assertSee('Topi');
    }

    public function test_search_tas()
    {
        $response = $this->get('/product/search?search=Tas');
        $response->assertStatus(200);
        $response->assertSee('Tas');
    }

    public function test_search_tumbler()
    {
        $response = $this->get('/product/search?search=Tumbler');
        $response->assertStatus(200);
        $response->assertSee('Tumbler');
    }

    public function test_search_tidak_ditemukan()
    {
        $response = $this->get('/product/search?search=TidakAdaProduk');
        $response->assertStatus(200);
        $response->assertDontSee('Kaos TShirt');
        // $response->assertSee('Tidak ada data'); // optional, kalau ada pesan fallback
    }
}
