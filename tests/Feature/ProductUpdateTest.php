<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Mockery;

class ProductUpdateTest extends TestCase
{
    public function test_update_with_empty_payload_fails_and_model_not_called()
    {
        $this->withoutMiddleware();

        $id = 12345;

        $mock = Mockery::mock(Product::class);
        $mock->shouldNotReceive('updateProduct');
        $this->app->instance(Product::class, $mock);

        $response = $this->putJson("/product/update/{$id}", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_name', 'product_type', 'product_category']);
    }

    public function test_update_with_short_name_fails_and_model_not_called()
    {
        $this->withoutMiddleware();

        $category = Category::factory()->create();
        $id = 54321;

    $mock = Mockery::mock(Product::class);
    $mock->shouldNotReceive('updateProduct');
    $this->app->instance(Product::class, $mock);

        $payload = [
            'product_name' => 'ab', // too short (min:3)
            'product_type' => 'TYPE1',
            'product_category' => $category->id,
            'product_description' => 'Some description'
        ];

        $response = $this->putJson("/product/update/{$id}", $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_name']);
    }

    public function test_update_with_valid_data_calls_model_updateProduct()
    {
        $this->withoutMiddleware();

        $category = Category::factory()->create();
        $id = 99999;

        $payload = [
            'product_name' => 'Valid Product Name',
            'product_type' => 'TYPEA',
            'product_category' => $category->id,
            'product_description' => 'Updated description'
        ];

        $mock = Mockery::mock(Product::class);
        $mock->shouldReceive('updateProduct')
            ->once()
            ->with($id, Mockery::on(function ($arg) {
                return is_array($arg) && array_key_exists('product_name', $arg);
            }))
            ->andReturn(['updated' => true]);

        $this->app->instance(Product::class, $mock);

        $response = $this->putJson("/product/update/{$id}", $payload);

        $response->assertStatus(200)
            ->assertExactJson(['updated' => true]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
