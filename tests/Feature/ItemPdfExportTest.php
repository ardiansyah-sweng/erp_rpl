<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Mockery;

class ItemPdfExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Registrasi route manual jika belum terdaftar di web.php
        Route::get('/item/pdf/product/{productType}', [\App\Http\Controllers\ItemController::class, 'exportByProductTypeToPdf']);
    }

    /** @test */
    public function it_generates_pdf_for_existing_product_type()
    {
        $productType = 'KAOS';

        // Simulasi data item
        $items = [
            (object)[
                'sku' => 'SKU001',
                'item_name' => 'Kaos Polos',
                'measurement_unit' => 'PCS',
                'selling_price' => 50000
            ],
        ];

        // Mocking static call ke model
        \Mockery::mock('alias:App\Models\Item')
            ->shouldReceive('getItemByType')
            ->with($productType)
            ->andReturn($items);

        // Mocking PDF response
        Pdf::shouldReceive('loadView')
            ->once()
            ->with('item.pdf_by_product', [
                'items' => $items,
                'productType' => $productType,
            ])
            ->andReturnSelf();

        Pdf::shouldReceive('setPaper')->once()->with('A4', 'portrait')->andReturnSelf();
        Pdf::shouldReceive('stream')
            ->once()
            ->with("Item_berdasarkan_product_type_{$productType}.pdf")
            ->andReturn(response('PDF content', 200, ['Content-Type' => 'application/pdf']));

        $response = $this->get("/item/pdf/product/{$productType}");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function it_redirects_with_error_if_product_type_not_found()
    {
        $productType = 'TIDAK_ADA';

        \Mockery::mock('alias:App\Models\Item')
            ->shouldReceive('getItemByType')
            ->with($productType)
            ->andReturn([]);

        $response = $this->get("/item/pdf/product/{$productType}");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tidak ada item dengan product type tersebut.');
    }
}
