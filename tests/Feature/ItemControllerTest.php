<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Mockery;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_generate_pdf_stub_for_items_by_product_id()
    {
        $dummyProductId = 'ABC123';

        // Mock PDF facade
        Pdf::shouldReceive('loadView')
            ->once()
            ->with('item.pdf_by_product', Mockery::on(function ($viewData) use ($dummyProductId) {
                return isset($viewData['items']) &&
                       isset($viewData['productId']) &&
                       $viewData['productId'] === $dummyProductId;
            }))
            ->andReturnSelf();

        Pdf::shouldReceive('setPaper')
            ->once()
            ->with('A4', 'portrait')
            ->andReturnSelf();

        Pdf::shouldReceive('download')
            ->once()
            ->with("daftar_item_product_{$dummyProductId}.pdf")
            ->andReturn(response('PDF dummy content', 200, [
                'Content-Type' => 'application/pdf',
            ]));

        $response = $this->get("/item/pdf/product/{$dummyProductId}");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
