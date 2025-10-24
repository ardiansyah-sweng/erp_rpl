<?php
// language: php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Mockery;
use Illuminate\Support\Collection;
use App\Models\Item as ItemModelAlias; // kept for reference if needed
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ItemController;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // ensure database migrations are fresh (RefreshDatabase trait will take care)
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test exportItemByCategoryToPdf returns PDF stream when items exist for the category
     */
    public function test_export_item_by_category_returns_pdf_when_items_exist()
    {
        // Arrange: create an item with a category
        $categoryId = 10;
        $categoryName = 'Electronics';

        // Mock the static model method so we don't hit the database JOINs
        Mockery::mock('alias:App\\Models\\Item')
            ->shouldReceive('getItemByCategory')
            ->with($categoryId)
            ->andReturn(collect([(object)['category_name' => $categoryName]]));

    // Prepare a mock PDF instance that will return a Response when stream() is called
    // Create the mock as an instance of the concrete PDF class so PHP return type hints are satisfied
    $pdfMock = Mockery::mock('Barryvdh\\DomPDF\\PDF');
        $pdfMock->shouldReceive('stream')
            ->once()
            ->with("item-kategori-{$categoryName}.pdf")
            ->andReturn(new Response('PDF_BYTES', 200));

        // Mock the Pdf facade to expect loadView with correct view and data
        Pdf::shouldReceive('loadView')
            ->once()
            ->withArgs(function ($view, $data) use ($categoryName) {
                // verify view name
                if ($view !== 'item.report_by_category') {
                    return false;
                }
                // verify categoryName passed to view
                if (!isset($data['categoryName']) || $data['categoryName'] !== $categoryName) {
                    return false;
                }
                // verify items collection exists and is not empty
                if (!isset($data['items'] )) {
                    return false;
                }
                // Accept any Traversable/Collection with at least one item
                $items = $data['items'];
                if (is_array($items) && count($items) === 0) {
                    return false;
                }
                // If it's a Collection, ensure not empty
                if (method_exists($items, 'isEmpty') && $items->isEmpty()) {
                    return false;
                }
                return true;
            })
            ->andReturn($pdfMock);

    // Act: call controller via container so redirect()/session are available
    $response = app()->call([app(ItemController::class), 'exportItemByCategoryToPdf'], ['categoryId' => $categoryId]);

        // Assert: response is the mocked PDF response
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('PDF_BYTES', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test exportItemByCategoryToPdf redirects back with error when no items exist for the category
     */

    public function test_export_item_by_category_redirects_back_when_no_items()
    {
        // Arrange: ensure no items for this category id
        $categoryId = 99;
        // Mock getItemByCategory to return an empty collection so controller redirects
        Mockery::mock('alias:App\\Models\\Item')
            ->shouldReceive('getItemByCategory')
            ->with($categoryId)
            ->andReturn(collect());

    // Act: call controller via container to allow redirect/session handling
    $response = app()->call([app(ItemController::class), 'exportItemByCategoryToPdf'], ['categoryId' => $categoryId]);

        // Assert: returned RedirectResponse and session has error message
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // RedirectResponse stores flashed data in session store; verify error message
        $session = $response->getSession();
        $this->assertNotNull($session);
        $this->assertTrue($session->has('error'));
        $this->assertEquals('Data tidak ditemukan untuk kategori ini.', $session->get('error'));
    }
}
