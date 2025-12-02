<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Mockery;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test */
    public function it_fails_validation_when_required_fields_are_missing()
    {
        // Act
        $response = $this->post(route('item.add'), []);

        // Assert
        $response->assertSessionHasErrors([
            'product_id',
            'sku',
            'item_name',
            'measurement_unit',
            'selling_price',
        ]);
    }

    /** @test */
    public function it_fails_when_product_id_is_not_4_characters()
    {
        $response = $this->post(route('item.add'), [
            'product_id' => 'P1', // invalid
            'sku' => 'SKU123',
            'item_name' => 'Valid Item',
            'measurement_unit' => 'PCS',
            'selling_price' => 10000,
        ]);

        $response->assertSessionHasErrors(['product_id']);
    }

    /** @test */
    public function it_fails_when_selling_price_is_negative()
    {
        $response = $this->post(route('item.add'), [
            'product_id' => 'P001',
            'sku' => 'SKU123',
            'item_name' => 'Valid Item',
            'measurement_unit' => 'PCS',
            'selling_price' => -10,
        ]);

        $response->assertSessionHasErrors(['selling_price']);
    }
}
