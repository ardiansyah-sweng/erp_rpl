<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierMaterialControllerTest extends TestCase
{
    public function test_returns_supplier_materials_by_product_type()
    {
        // Misalnya kita uji dengan product_type 'RM' 
        $response = $this->get('/supplier-material/RM');

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
                'master_product_name'
            ]
        ]);
    }
}