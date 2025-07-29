<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Item;


class ProductTest extends TestCase
{
    // use RefreshDatabase;

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
    public function delete_product_ketika_digunakan_di_items()
    {
        $product = Product::find(9);

        $this->assertTrue(Item::where('product_id', $product->product_id)->exists());

        $result = Product::deleteProductById($product->id);

        $this->assertFalse($result);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
    
    /** @test */
    public function delete_product_ketika_tidak_digunakan_di_items()
    {
    // Arrange: Buat produk baru di database
    $product = Product::create([
        'product_id' => 'ABC2',
        'product_name' => 'ABC',
        'name' => 'Test Product',
        'product_type' => 'FG',
        'product_category' => 1,
        'product_description' => 'hanya nyoba'
    ]);

    // Pastikan produk berhasil masuk ke database
    $this->assertDatabaseHas('products', ['id' => $product->id]);

    $this->assertFalse(Item::where('product_id', $product->product_id)->exists());

    $result = Product::deleteProductById($product->id);
    $this->assertTrue($result);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
