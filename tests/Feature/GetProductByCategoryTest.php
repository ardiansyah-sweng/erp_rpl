<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\ProductType;

class GetProductByCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected int $kategoriMakanan = 1;
    protected int $kategoriMinuman = 2;

    public function test_returns_products_matching_category()
    {
        Product::create([
            'product_id' => 'M001',
            'product_name' => 'Nasi Goreng',
            'product_type' => ProductType::FG,
            'product_category' => $this->kategoriMakanan,
            'product_description' => 'Nasi goreng spesial dengan telur dan ayam',
        ]);

        Product::create([
            'product_id' => 'M002',
            'product_name' => 'Sate Ayam',
            'product_type' => ProductType::FG,
            'product_category' => $this->kategoriMakanan,
            'product_description' => 'Sate ayam dengan bumbu kacang',
        ]);

        Product::create([
            'product_id' => 'D001',
            'product_name' => 'Teh Manis',
            'product_type' => ProductType::FG,
            'product_category' => $this->kategoriMinuman,
            'product_description' => 'Teh manis dingin menyegarkan',
        ]);

        $results = Product::getProductByCategory($this->kategoriMakanan);

        $this->assertEquals(2, $results->total());
        $this->assertTrue($results->pluck('product_name')->contains('Nasi Goreng'));
        $this->assertTrue($results->pluck('product_name')->contains('Sate Ayam'));
    }

    public function test_does_not_return_products_from_other_categories()
    {
        Product::create([
            'product_id' => 'D002',
            'product_name' => 'Jus Jeruk',
            'product_type' => ProductType::FG,
            'product_category' => $this->kategoriMinuman,
            'product_description' => 'Jus jeruk segar alami',
        ]);

        $results = Product::getProductByCategory($this->kategoriMakanan);

        $this->assertEquals(0, $results->total());
    }

    public function test_returns_empty_result_when_database_is_empty()
    {
        $results = Product::getProductByCategory($this->kategoriMakanan);

        $this->assertEquals(0, $results->total());
    }
}
