<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Enums\ProductType;

class CountProductByTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure migrations ran for products table
        $this->artisan('migrate');
    }

    /**
     * Test: returns correct count for an existing product type
     */
    public function test_countProductByProductType_returns_correct_count_for_existing_type()
    {
        // Arrange - create 3 products with type RM and some others
        Product::factory()->count(3)->create(['type' => ProductType::RM->value]);
        Product::factory()->count(2)->create(['type' => ProductType::FG->value]);

        // Act
        $count = Product::countProductByProductType(ProductType::RM->value);

        // Assert
        $this->assertEquals(3, $count);
    }

    /**
     * Test: returns zero when there are no products of requested type
     */
    public function test_countProductByProductType_returns_zero_when_no_products_of_type()
    {
        // Arrange - create some products but none with HFG
        Product::factory()->count(4)->create(['type' => ProductType::FG->value]);

        // Act
        $count = Product::countProductByProductType(ProductType::HFG->value);

        // Assert
        $this->assertEquals(0, $count);
    }

    /**
     * Test: counts only products of the requested type and ignores others
     */
    public function test_countProductByProductType_counts_only_specific_type()
    {
        // Arrange - create mixed products
        Product::factory()->count(5)->create(['type' => ProductType::RM->value]);
        Product::factory()->count(2)->create(['type' => ProductType::FG->value]);

        // Act
        $countFG = Product::countProductByProductType(ProductType::FG->value);
        $countRM = Product::countProductByProductType(ProductType::RM->value);

        // Assert
        $this->assertEquals(2, $countFG);
        $this->assertEquals(5, $countRM);
    }
}
