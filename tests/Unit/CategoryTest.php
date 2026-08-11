<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_count_product_by_category()
    {
        // Simulasi config kolom
        config([
            'db_constants.column.products' => [
                'product_category' => 'product_category',
            ],
        ]);

        // Arrange: Buat 3 produk
        Product::factory()->create(['product_category' => 1]); // match
        Product::factory()->create(['product_category' => 1]); // match
        Product::factory()->create(['product_category' => 2]); // beda

        // Act: Panggil fungsi yang ingin di-test
        $expectedCount = 2;
        $result = Product::countProductByCategory(1);

        // Assert
        $this->assertEquals($expectedCount, $result);
    }

    /** @test */
    public function it_returns_zero_if_no_product_found()
    {
        config([
            'db_constants.column.products' => [
                'product_category' => 'product_category',
            ],
        ]);

        // Act
        $expectedCount = 0;
        $result = Product::countProductByCategory(99); // kategori tidak ada

        // Assert
        $this->assertEquals($expectedCount, $result);
    }
}
