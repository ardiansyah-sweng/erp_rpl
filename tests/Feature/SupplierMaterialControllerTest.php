<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialControllerTest extends TestCase
{
    public function test_returns_supplier_materials_by_product_type()
    {
        // Setup: Insert dummy product
        DB::table('products')->insert([
            'product_id' => 'P001',
            'product_name' => 'Bahan Mentah A',
            'product_type' => 'RM',
            'product_category' => '1',
            'product_description' => 'Deskripsi untuk bahan mentah A.'
        ]);

        // Setup: Insert dummy supplier product
        DB::table('supplier_product')->insert([
            'supplier_id' => 'SPL001',
            'company_name' => 'PT Contoh Supplier',
            'product_id' => 'P001',
            'product_name' => 'Bahan Mentah A',
            'base_price' => 50000
        ]);

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
