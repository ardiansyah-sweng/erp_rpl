<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierMaterialControllerTest extends TestCase
{
    public function test_returns_supplier_materials_by_product_type()
    {
        // Misal kita uji dengan product_type 'vitca' (atau yang memang ada di tabel `products`)
        $response = $this->get('/supplier-material/vitca');

        $response->assertStatus(200); // Pastikan HTTP status = 200 OK

        // Cek struktur JSON yang dikembalikan
        $response->assertJsonStructure([
            '*' => [
                'supplier_id',
                'company_name',
                'product_id',
                'product_name',
                'product_type',
                'base_price',
            ]
        ]);
    }
}
