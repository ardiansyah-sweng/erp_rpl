<?php

namespace Tests\Feature;

SupplierMaterialController-zidane
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
=======
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
 SupplierMaterialController-zidane
                'base_price',
                'product_type',
            ]
        ]);
    }

                'base_price'
            ]);
        }
 development
}
