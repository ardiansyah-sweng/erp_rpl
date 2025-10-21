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

  public function testReturnsSupplierMaterialsByProductType()
    {
        // Gunakan data nyata dari database
        $supplierId = 'SUP001';
        $productType = 'FG';

        // Kirim request ke endpoint
        $response = $this->get("/supplier-material/{$supplierId}/{$productType}");

        // Pastikan status sukses
        $response->assertStatus(200);

        // Validasi struktur JSON (walau kosong, struktur tetap valid)
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