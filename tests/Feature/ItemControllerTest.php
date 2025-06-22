<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\MeasurementUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Mockery;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
public function it_can_generate_pdf_for_items_by_product_id()
{
    $productId = 'KAOS';

    // Mock PDF
    Pdf::shouldReceive('loadView')
        ->once()
        ->with('item.pdf_by_product', Mockery::on(function ($viewData) use ($productId) {
            return isset($viewData['items']) &&
                   isset($viewData['productId']) &&
                   $viewData['productId'] === $productId;
        }))
        ->andReturnSelf();

    Pdf::shouldReceive('setPaper')->once()->with('A4', 'portrait')->andReturnSelf();
    Pdf::shouldReceive('download')->once()->with("Item_berdasarkan_category_{$productId}.pdf")
        ->andReturn(response('PDF content', 200, ['Content-Type' => 'application/pdf']));

    $response = $this->get("/item/pdf/product/{$productId}");

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
}
    /** @test */
    public function it_returns_error_if_no_items_for_product_id()
    {
        $productId = 'NONEXISTENT';

        // Tidak perlu mocking PDF karena tidak dipanggil
        $response = $this->get("/item/pdf/product/{$productId}");

        $response->assertRedirect(); // Redirect back
        $response->assertSessionHas('error', 'Tidak ada item dengan product ID tersebut.');
    }
}
