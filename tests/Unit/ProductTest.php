<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Item;


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

    /** @test */
    public function delete_product_ketika_tidak_digunakan_di_items()
    {
        //Buat produk dengan product_id khusus
        $product = Product::create([
            'product_id' => 'ABC2',
            'product_name' => 'ABC',
            'name' => 'Test Product',
            'product_type' => 'FG',
            'product_category' => 1,
            'product_description' => 'hanya nyoba'
        ]);

        // $product = Product::find(25);

        $this->assertFalse(Item::where('product_id', 'ABC2')->exists());

        $result = Product::deleteProductById($product->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
