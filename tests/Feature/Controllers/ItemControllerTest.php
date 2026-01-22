<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $itemMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock untuk static method menggunakan 'alias:'
        $this->itemMock = Mockery::mock('alias:' . Item::class);
        
        $this->controller = new ItemController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test update item dengan data valid
     */
    public function test_update_item_dengan_data_valid()
    {
        // Arrange: Buat mock Item
        $itemId = 1;
        $validatedData = [
            'id' => $itemId,
            'sku' => 'SKU-123',
            'item_name' => 'Item Test Updated',
        ];

        // Mock static method updateItem
        $this->itemMock->shouldReceive('updateItem')
            ->once()
            ->with($itemId, $validatedData)
            ->andReturn((object) $validatedData);

        // Buat request dengan data valid
        $request = Request::create('/item/update/' . $itemId, 'PUT', $validatedData);

        // Act: Panggil method updateItem
        $response = $this->controller->updateItem($request, $itemId);

        // Assert: Pastikan redirect dengan success message
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Item berhasil diperbarui.', session('success'));
    }

    /**
     * Test update item ketika item tidak ditemukan
     */
    public function test_update_item_ketika_item_tidak_ditemukan()
    {
        // Arrange
        $itemId = 999;
        $validatedData = [
            'id' => $itemId,
            'sku' => 'SKU-123',
            'item_name' => 'Item Test',
        ];

        // Mock static method updateItem
        $this->itemMock->shouldReceive('updateItem')
            ->once()
            ->with($itemId, $validatedData)
            ->andReturn(null);

        $request = Request::create('/item/update/' . $itemId, 'PUT', $validatedData);

        // Act
        $response = $this->controller->updateItem($request, $itemId);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Item tidak ditemukan.', session('error'));
    }

    /**
     * Test validasi gagal karena id tidak ada
     */
    public function test_update_item_validasi_gagal_id_required()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $itemId = 1;
        $invalidData = [
            // 'id' => missing
            'sku' => 'SKU-123',
            'item_name' => 'Item Test',
        ];

        $request = Request::create('/item/update/' . $itemId, 'PUT', $invalidData);
        
        $this->controller->updateItem($request, $itemId);
    }

    /**
     * Test validasi gagal karena sku tidak ada
     */
    public function test_update_item_validasi_gagal_sku_required()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $itemId = 1;
        $invalidData = [
            'id' => $itemId,
            // 'sku' => missing
            'item_name' => 'Item Test',
        ];

        $request = Request::create('/item/update/' . $itemId, 'PUT', $invalidData);
        
        $this->controller->updateItem($request, $itemId);
    }

    /**
     * Test validasi gagal karena item_name tidak ada
     */
    public function test_update_item_validasi_gagal_item_name_required()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $itemId = 1;
        $invalidData = [
            'id' => $itemId,
            'sku' => 'SKU-123',
            // 'item_name' => missing
        ];

        $request = Request::create('/item/update/' . $itemId, 'PUT', $invalidData);
        
        $this->controller->updateItem($request, $itemId);
    }

    /**
     * Test validasi gagal karena id bukan integer
     */
    public function test_update_item_validasi_gagal_id_bukan_integer()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $itemId = 1;
        $invalidData = [
            'id' => 'bukan-integer',
            'sku' => 'SKU-123',
            'item_name' => 'Item Test',
        ];

        $request = Request::create('/item/update/' . $itemId, 'PUT', $invalidData);
        
        $this->controller->updateItem($request, $itemId);
    }

    /**
     * Test validasi gagal karena sku melebihi max length
     */
    public function test_update_item_validasi_gagal_sku_terlalu_panjang()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $itemId = 1;
        $invalidData = [
            'id' => $itemId,
            'sku' => str_repeat('A', 51), // 51 karakter, melebihi max 50
            'item_name' => 'Item Test',
        ];

        $request = Request::create('/item/update/' . $itemId, 'PUT', $invalidData);
        
        $this->controller->updateItem($request, $itemId);
    }

    /**
     * Test validasi gagal karena item_name melebihi max length
     */
    public function test_update_item_validasi_gagal_item_name_terlalu_panjang()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $itemId = 1;
        $invalidData = [
            'id' => $itemId,
            'sku' => 'SKU-123',
            'item_name' => str_repeat('A', 101), // 101 karakter, melebihi max 100
        ];

        $request = Request::create('/item/update/' . $itemId, 'PUT', $invalidData);
        
        $this->controller->updateItem($request, $itemId);
    }

    /**
     * Test update item dengan sku di batas maksimal (50 karakter)
     */
    public function test_update_item_dengan_sku_max_length()
    {
        $itemId = 1;
        $validatedData = [
            'id' => $itemId,
            'sku' => str_repeat('A', 50), // Tepat 50 karakter
            'item_name' => 'Item Test',
        ];

        // Mock static method updateItem
        $this->itemMock->shouldReceive('updateItem')
            ->once()
            ->with($itemId, $validatedData)
            ->andReturn((object) $validatedData);

        $request = Request::create('/item/update/' . $itemId, 'PUT', $validatedData);

        $response = $this->controller->updateItem($request, $itemId);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Item berhasil diperbarui.', session('success'));
    }

    /**
     * Test update item dengan item_name di batas maksimal (100 karakter)
     */
    public function test_update_item_dengan_item_name_max_length()
    {
        $itemId = 1;
        $validatedData = [
            'id' => $itemId,
            'sku' => 'SKU-123',
            'item_name' => str_repeat('A', 100), // Tepat 100 karakter
        ];

        // Mock static method updateItem
        $this->itemMock->shouldReceive('updateItem')
            ->once()
            ->with($itemId, $validatedData)
            ->andReturn((object) $validatedData);

        $request = Request::create('/item/update/' . $itemId, 'PUT', $validatedData);

        $response = $this->controller->updateItem($request, $itemId);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Item berhasil diperbarui.', session('success'));
    }
}