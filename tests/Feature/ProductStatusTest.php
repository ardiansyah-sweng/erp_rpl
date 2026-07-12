<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductStatusTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_can_be_deactivated_without_being_deleted(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->patch(route('product.status.update', $product->id), [
            'is_active' => '0',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Produk berhasil dinonaktifkan.');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => 0,
        ]);
    }

    public function test_product_list_can_be_filtered_by_inactive_status(): void
    {
        $category = Category::factory()->create();
        $inactiveProduct = Product::factory()->create([
            'category' => $category->id,
            'is_active' => false,
        ]);
        $activeProduct = Product::factory()->create([
            'category' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('product.list', ['status' => 'inactive']));

        $response->assertOk();
        $response->assertViewHas('products', function ($products) use ($inactiveProduct, $activeProduct) {
            return $products->contains('id', $inactiveProduct->id)
                && !$products->contains('id', $activeProduct->id);
        });
    }

    public function test_product_status_requires_a_boolean_value(): void
    {
        $product = Product::factory()->create();

        $response = $this->from(route('product.list'))
            ->patch(route('product.status.update', $product->id), [
                'is_active' => 'not-a-status',
            ]);

        $response->assertRedirect(route('product.list'));
        $response->assertSessionHasErrors('is_active');
    }
}
