<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class GetProductByKeywordTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_empty_result_when_database_is_empty()
    {
        // Kondisi: Tidak ada data
        $result = Product::getProductByKeyword('anything');

        // Periksa tidak ada error dan hasil kosong
        $this->assertEquals(0, $result->total());
    }

    public function test_it_can_be_called_without_keyword_and_returns_empty_paginated_result()
    {
        // Kondisi: Tidak ada keyword dan database kosong
        $result = Product::getProductByKeyword();

        $this->assertEquals(0, $result->total());
        $this->assertEquals(1, $result->currentPage()); // halaman awal
        $this->assertEquals(10, $result->perPage()); // default paginate
    }
}
