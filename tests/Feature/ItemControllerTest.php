<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\MeasurementUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Mockery;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_generate_pdf_for_items_by_product_id()
{
    // Buat unit & item dummy
    $unit = MeasurementUnit::create([
        'unit_name' => 'Pcs',
        'abbreviation' => 'pcs',
    ]);

    Item::create([
        'product_id' => 'KAOS',
        'sku' => 'KAOS-001',
        'item_name' => 'Kaos Lengan Pendek',
        'measurement_unit' => $unit->id,
        'selling_price' => 50000
    ]);

    // Fake PDF facade
    Pdf::shouldReceive('loadView')
        ->once()
        ->with('item.pdf_by_product', \Mockery::on(function ($viewData) {
            return isset($viewData['items']) && isset($viewData['productId']);
        }))
        ->andReturnSelf();

    Pdf::shouldReceive('setPaper')->once()->with('A4', 'portrait')->andReturnSelf();
    Pdf::shouldReceive('download')->once()->andReturn(response('PDF content', 200, [
        'Content-Type' => 'application/pdf',
    ]));

    $response = $this->get('/item/pdf/product/KAOS');

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
}
}
