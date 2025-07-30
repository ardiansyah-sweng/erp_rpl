<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class getSupplierMaterialByProductTypeTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_returns_supplier_materials_for_valid_product_type()
    {
        // Ambil data supplier_id dan product_type valid dari database
        $validData = DB::table('supplier_product')
            ->join('products', DB::raw("SUBSTRING_INDEX(supplier_product.product_id, '-', 1)"), '=', 'products.product_id')
            ->select('supplier_product.supplier_id', 'products.product_type')
            ->whereIn('products.product_type', ['HFG', 'FG', 'RM'])
            ->first();

        if (!$validData) {
            $this->markTestSkipped('Data tidak tersedia untuk product_type valid.');
        }

        $supplierId = $validData->supplier_id;
        $productType = $validData->product_type;

        // Kirim request ke endpoint
        $response = $this->get("/supplier-material/{$supplierId}/{$productType}");

        // Validasi response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'supplier_id',
                'company_name',
                'product_id',
                'product_name',
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
        $supplierId = DB::table('supplier_product')->value('supplier_id');

        if (!$supplierId) {
            $this->markTestSkipped('Supplier ID tidak ditemukan untuk pengujian.');
        }

        $invalidType = 'XYZ'; // produk tidak valid

        // Kirim request ke endpoint dengan tipe produk tidak valid
        $response = $this->get("/supplier-material/{$supplierId}/{$invalidType}");

        // Validasi bahwa error ditangkap
        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Invalid product type',
        ]);
    }
}
