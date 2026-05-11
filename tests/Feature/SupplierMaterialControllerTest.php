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
 public function testReturnsSupplierMaterialsByCategory()
{
    $supplierId = 'SUP014';
    $kategory = 18;

    $response = $this->get("/supplier-material/category/{$kategory}/{$supplierId}");

    $response->assertStatus(200);

    $response->assertJsonStructure([
        '*' => [
            'supplier_id',
            'company_name',
            'product_id',
            'product_name',
            'product_category',
            'base_price',
            'item_name',
            'measurement_unit',
            'stock_unit',
        ]
    ]);
}

public function testReturnsCategoryNotFound()
{
    // Gunakan kategori yang tidak ada di database
    $supplierId = 'SUP014';
    $kategory = 99;

    $response = $this->get("/supplier-material/category/{$kategory}/{$supplierId}");

    // Harusnya return 404 karena data kosong
    $response->assertStatus(404);

    // Validasi pesan error
    $response->assertJson([
        'message' => 'Tidak ada data ditemukan'
    ]);
}

public function testReturnsSupplierNotFound()
{
    // Gunakan supplier_id yang tidak ada di database
    $supplierId = 'SUP999';
    $kategory = 1;

    $response = $this->get("/supplier-material/category/{$kategory}/{$supplierId}");

    // Harusnya return 404 karena data kosong
    $response->assertStatus(404);

    // Validasi pesan error
    $response->assertJson([
        'message' => 'Tidak ada data ditemukan'
    ]);
}
}