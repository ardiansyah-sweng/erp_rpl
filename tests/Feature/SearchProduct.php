<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchProduct extends TestCase
{
    public function test_search_existing_product()
    {

        $response = $this->get('/product/search/Tas');
        $response->assertStatus(200);
        $response->assertViewIs('product.list');
        $response->assertViewHas('products', function ($products) {
            return $products->contains('product_id', 'TASS');
        });
    }
}
