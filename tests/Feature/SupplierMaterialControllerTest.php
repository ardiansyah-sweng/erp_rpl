<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SupplierMaterialControllerTest extends TestCase
{
    // Helper: mock DB builder untuk query yang ada DB::raw() di join
    private function mockDbQuery(array $returnData): void
    {
        // Cara paling aman: DB::raw cuma balikin string yang dikirim
        DB::shouldReceive('raw')
            ->andReturnUsing(fn($value) => $value);

        // Mock query builder
        $builder = \Mockery::mock(\Illuminate\Database\Query\Builder::class);
        $builder->shouldReceive('join')->andReturn($builder);
        $builder->shouldReceive('where')->andReturn($builder);
        $builder->shouldReceive('select')->andReturn($builder);
        $builder->shouldReceive('get')->andReturn(collect($returnData));

        DB::shouldReceive('table')
            ->with('supplier_product')
            ->andReturn($builder);
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
    }

    public function testReturnsSupplierMaterialsByProductType()
    {
        $this->mockDbQuery([
            (object)[
                'supplier_id'      => 'SUP001',
                'company_name'     => 'PT Test',
                'product_id'       => 'FG001-A',
                'product_name'     => 'Produk Test',
                'product_type'     => 'FG',
                'base_price'       => 50000,
                'item_name'        => 'Item Test',
                'measurement_unit' => 'pcs',
                'stock_unit'       => 'pcs',
            ]
        ]);

        $response = $this->get('/supplier-material/SUP001/FG');

        $response->assertStatus(200);
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

    public function testReturnsSupplierMaterialsByCategory()
    {
        $this->mockDbQuery([
            (object)[
                'supplier_id'      => 'SUP014',
                'company_name'     => 'PT Supplier',
                'product_id'       => 'CAT018-A',
                'product_name'     => 'Produk Kategori',
                'product_category' => 18,
                'base_price'       => 75000,
                'item_name'        => 'Item Kategori',
                'measurement_unit' => 'kg',
                'stock_unit'       => 'kg',
            ]
        ]);

        $response = $this->get('/supplier-material/category/18/SUP014');

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
        // Kembalikan collection kosong → controller return 404
        $this->mockDbQuery([]);

        $response = $this->get('/supplier-material/category/99/SUP014');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Tidak ada data ditemukan']);
    }

    public function testReturnsSupplierNotFound()
    {
        // Kembalikan collection kosong → controller return 404
        $this->mockDbQuery([]);

        $response = $this->get('/supplier-material/category/1/SUP999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Tidak ada data ditemukan']);
    }
}