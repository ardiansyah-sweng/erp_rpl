<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create item with valid data
     */
    public function test_create_item_with_valid_data(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'item_name' => 'Test Item',
            'measurement_unit' => 'pcs',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect(route('item.list'));
        $this->assertDatabaseHas('items', [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'name' => 'Test Item',
            'measurement' => 'pcs',
            'selling_price' => 10000,
        ]);
    }

    /**
     * Test create item with empty product_id
     */
    public function test_create_item_with_empty_product_id(): void
    {
        $data = [
            'product_id' => '',
            'sku' => 'SKU123',
            'item_name' => 'Test Item',
            'measurement_unit' => 'pcs',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['product_id']);
    }

    /**
     * Test create item with product_id too short
     */
    public function test_create_item_with_product_id_too_short(): void
    {
        $data = [
            'product_id' => 'PRO',
            'sku' => 'SKU123',
            'item_name' => 'Test Item',
            'measurement_unit' => 'pcs',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['product_id']);
    }

    /**
     * Test create item with empty sku
     */
    public function test_create_item_with_empty_sku(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => '',
            'item_name' => 'Test Item',
            'measurement_unit' => 'pcs',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['sku']);
    }

    /**
     * Test create item with empty item_name
     */
    public function test_create_item_with_empty_item_name(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'item_name' => '',
            'measurement_unit' => 'pcs',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['item_name']);
    }

    /**
     * Test create item with item_name too short
     */
    public function test_create_item_with_item_name_too_short(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'item_name' => 'AB',
            'measurement_unit' => 'pcs',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['item_name']);
    }

    /**
     * Test create item with empty measurement_unit
     */
    public function test_create_item_with_empty_measurement_unit(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'item_name' => 'Test Item',
            'measurement_unit' => '',
            'selling_price' => 10000,
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['measurement_unit']);
    }

    /**
     * Test create item with empty selling_price
     */
    public function test_create_item_with_empty_selling_price(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'item_name' => 'Test Item',
            'measurement_unit' => 'pcs',
            'selling_price' => '',
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['selling_price']);
    }

    /**
     * Test create item with invalid selling_price
     */
    public function test_create_item_with_invalid_selling_price(): void
    {
        $data = [
            'product_id' => 'PROD',
            'sku' => 'SKU123',
            'item_name' => 'Test Item',
            'measurement_unit' => 'pcs',
            'selling_price' => 'abc',
        ];

        $response = $this->post(route('item.add'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['selling_price']);
    }

    /**
     * Test user can access item create form
     */
    public function test_user_can_access_item_create_form(): void
    {
        $response = $this->get(route('item.add'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Item Produk');
        $response->assertSee('product_id');
        $response->assertSee('sku');
        $response->assertSee('item_name');
        $response->assertSee('measurement_unit');
        $response->assertSee('selling_price');
    }
}
