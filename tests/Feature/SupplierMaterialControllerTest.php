<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialControllerTest extends TestCase
{
    public function test_returns_supplier_materials_by_product_type()
    {
        // Kirim request dengan 2 parameter: supplier_id dan product_type
        $response = $this->get('/supplier-material/SPL001/RM');

        // Pastikan responsenya sukses
        $response->assertStatus(200);

        // Pastikan struktur JSON-nya sesuai
        $response->assertJsonStructure([
            '*' => [
                'supplier_id',
                'company_name',
                'product_id',
                'product_name',
                'base_price',
                'product_type',
            ]
        ]);
    }
}

