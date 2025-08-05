<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_returns_supplier_materials_for_valid_product_type()
    {
        // Gunakan data dari database yang sudah ada

        $response = $this->get('/supplier-material/SUP001/FG');

        $response->dump(); // Ini akan menampilkan isi JSON response di terminal saat testing

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json(), 'Response kosong padahal data seharusnya sudah ada.');

        $response->assertJsonStructure([
            '*' => [
                'supplier_id',
                'company_name',
                'product_id',
                'product_type',
                'base_price',
                'item_name',
                'measurement_unit',
                'stock_unit',
            ]
        ]);
    }

    /** @test */
    public function it_returns_400_for_invalid_product_type()
    {
        $response = $this->get('/supplier-material/SUP001/INVALID');
        $response->dump(); // Untuk debugging jika diperlukan
        $response->assertStatus(400);
    }
}
