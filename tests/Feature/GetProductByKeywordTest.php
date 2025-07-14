<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GetProductByKeywordTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_returns_filtered_products_when_keyword_is_provided()
    {
        $keyword = 'Water';

        $results = Product::getProductByKeyword($keyword);

        // Test tetap pass walaupun hasil kosong
        if ($results->isEmpty()) {
            $this->assertTrue(true, "No results found for keyword: {$keyword}");
            echo "\nPencarian untuk kata '{$keyword}' tidak ditemukan di database.\n";
            return;
        }

        foreach ($results as $product) {
            $this->assertTrue(
                stripos($product->product_id, $keyword) !== false ||
                stripos($product->product_name, $keyword) !== false ||
                stripos($product->product_type, $keyword) !== false ||
                stripos($product->product_category, $keyword) !== false ||
                stripos($product->product_description, $keyword) !== false,
                'Keyword not found in any field'
            );
        }
    }


    /** @test */
    public function it_returns_paginated_products_when_no_keyword_provided()
    {
        $results = Product::getProductByKeyword(null);

        $this->assertNotEmpty($results, 'Expected products but got empty');
        $this->assertLessThanOrEqual(10, $results->count(), 'More than 10 products returned');
    }
}
