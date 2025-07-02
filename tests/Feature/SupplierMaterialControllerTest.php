<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SupplierMaterial;

class SupplierMaterialControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        config(['db_constants.table.supplier' => 'supplier_product']);
        config(['db_constants.column.supplier' => [
            'supplier_id',
            'company_name',
            'product_id',
            'product_name',
            'base_price'
        ]]);
    }


    public function testAddSupplierMaterialSuccessfully()
    {
        $data = [
            'supplier_id'   => 'SUP200',
            'company_name'  => 'Tes Controller',
            'product_id'    => 'P004-aut',
            'product_name'  => 'Oblong Controller',
            'base_price'    => '54315'
        ];

        $response = $this->post('/supplier/material/add', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas(config('db_constants.table.supplier'), [
            'supplier_id'  => 'SUP200',
            'product_id'   => 'P004-aut',
            'product_name' => 'Oblong Controller',
            'base_price'   => '54315'
        ]);
    }

        public function testAddSupplierMaterialFailsWithEmptyData()
        {
            $response = $this->post('/supplier/material/add', []);

            $response->assertSessionHasErrors([
                'supplier_id',
                'company_name',
                'product_id',
                'product_name',
                'base_price'
            ]);
        }
        
        public function test_returns_supplier_materials_by_product_type()
    {
        // Kirim request langsung tanpa insert dummy data
        $response = $this->get('/supplier-material/SUP005/RM');

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

