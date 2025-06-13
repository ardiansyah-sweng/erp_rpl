<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    /** @test */
    public function it_returns_products_that_match_kaos_in_name()
    {
        $response = $this->get('/product/search?search=Kaos');
        
        $response->assertStatus(200);
        $response->assertSee('Kaos TShirt');
        $response->assertDontSee('Topi');
        $response->assertDontSee('Tumbler');
    }

    /** @test */
    public function it_returns_products_that_match_topi_in_name()
    {
        $response = $this->get('/product/search?search=Topi');
        
        $response->assertStatus(200);
        $response->assertSee('Topi');
        $response->assertDontSee('Kaos TShirt');
        $response->assertDontSee('Tas');
    }

    /** @test */
    public function it_returns_products_that_match_tumbler_in_name()
    {
        $response = $this->get('/product/search?search=Tumbler');
        
        $response->assertStatus(200);
        $response->assertSee('Tumbler');
        $response->assertDontSee('Topi');
        $response->assertDontSee('Tanjak');
    }

    /** @test */
    public function it_returns_products_that_match_jam_in_name()
    {
        $response = $this->get('/product/search?search=Jam');
        
        $response->assertStatus(200);
        $response->assertSee('Jam');
        $response->assertDontSee('Tumbler');
        $response->assertDontSee('Kaos TShirt');
    }

    /** @test */
    public function it_returns_products_that_match_calendar_in_name()
    {
        $response = $this->get('/product/search?search=Calendar');
        
        $response->assertStatus(200);
        $response->assertSee('Calendar');
        $response->assertDontSee('Jam');
        $response->assertDontSee('Kaos TShirt');
    }

    /** @test */
    public function it_returns_products_that_match_gantungan_in_name()
    {
        $response = $this->get('/product/search?search=Gantungan');
        
        $response->assertStatus(200);
        $response->assertSee('Gantungan Kunci');
        $response->assertDontSee('Calendar');
        $response->assertDontSee('Jam');
    }

    /** @test */
    public function it_returns_products_that_match_bros_in_name()
    {
        $response = $this->get('/product/search?search=Bros');
        
        $response->assertStatus(200);
        $response->assertSee('Bros PIN');
        $response->assertDontSee('Gantungan Kunci');
        $response->assertDontSee('Dompet');
    }

    /** @test */
    public function it_returns_products_that_match_dompet_in_name()
    {
        $response = $this->get('/product/search?search=Dompet');
        
        $response->assertStatus(200);
        $response->assertSee('Dompet');
        $response->assertDontSee('Bros PIN');
        $response->assertDontSee('Kue Bolen');
    }

    /** @test */
    public function it_returns_products_that_match_kue_in_name()
    {
        $response = $this->get('/product/search?search=Kue');
        
        $response->assertStatus(200);
        $response->assertSee('Kue Bolen');
        $response->assertDontSee('Dompet');
        $response->assertDontSee('Pempek');
    }

    /** @test */
    public function it_returns_products_that_match_pempek_in_name()
    {
        $response = $this->get('/product/search?search=Pempek');
        
        $response->assertStatus(200);
        $response->assertSee('Pempek');
        $response->assertDontSee('Kue Bolen');
        $response->assertDontSee('Salicylic');
    }

    /** @test */
    public function it_returns_products_case_insensitive_search()
    {
        $response = $this->get('/product/search?search=kaos');
        
        $response->assertStatus(200);
        $response->assertSee('Kaos TShirt');
        $response->assertDontSee('Topi');
    }

    /** @test */
    public function it_returns_products_with_partial_name_match()
    {
        $response = $this->get('/product/search?search=Gan');
        
        $response->assertStatus(200);
        $response->assertSee('Gantungan Kunci');
        $response->assertDontSee('Calendar');
    }

    /** @test */
    public function it_returns_all_products_when_no_search_query_is_provided()
    {
        $response = $this->get('/product/search');
        
        $response->assertStatus(200);
        $response->assertSee('Kaos TShirt');
        $response->assertSee('Topi');
        $response->assertSee('Tumbler');
        $response->assertSee('Jam');
        $response->assertSee('Gantungan Kunci');
    }

    /** @test */
    public function it_returns_empty_result_for_non_existing_product()
    {
        $response = $this->get('/product/search?search=ProdukTidakAda');
        
        $response->assertStatus(200);
        $response->assertDontSee('Kaos TShirt');
        $response->assertDontSee('Topi');
        $response->assertDontSee('Tumbler');
    }

    /** @test */
    public function it_returns_products_with_space_in_search_query()
    {
        $response = $this->get('/product/search?search=Kaos TShirt');
        
        $response->assertStatus(200);
        $response->assertSee('Kaos TShirt');
        $response->assertDontSee('Topi');
    }
}