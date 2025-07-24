<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;


class ProductTest extends TestCase
{
    use RefreshDatabase;

public function test_get_product_by_type()
{
    // Arrange
    Product::factory()->create(['product_type' => 'FG']);
    Product::factory()->create(['product_type' => 'RM']);
    Product::factory()->create(['product_type' => 'HFG']);

    // Act
    $result = Product::getProductByType('FG');

    // Assert
    $this->assertCount(1, $result);
    foreach ($result as $product) {
        $this->assertEquals('FG', $product->product_type);
    }
}

}
