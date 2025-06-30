<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategorySearchTest extends TestCase
{
    /** @test */
    public function it_can_search_categories_by_name()
    {
        

        $response = $this->get('/category/search?q=Pakaian');

        $response->assertStatus(200);
        $response->assertSeeText('Pakaian & Aksesoris');
        $response->assertDontSeeText('Elektronik');
    }

    /** @test */
    public function it_returns_all_categories_if_query_is_empty()
    {
       
        $response = $this->get('/category/search');

        $response->assertStatus(200);
        $response->assertSeeText('Olahraga');
        $response->assertSeeText('Mainan');
    }

    /** @test */
    public function it_shows_nothing_when_nothing_matches()
    {
        $response = $this->get('/category/search?q=XYZ123TidakAda');

        $response->assertStatus(200);
        $response->assertDontSeeText('Pakaian & Aksesoris');
        $response->assertDontSeeText('Elektronik');
        $response->assertDontSeeText('Mainan');
    }
}
