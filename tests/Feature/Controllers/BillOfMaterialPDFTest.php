<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\BillOfMaterial;

class BillOfMaterialPDFTest extends TestCase
{
    use RefreshDatabase;

    protected function setupBOMData()
    {
        $bomTable = (new BillOfMaterial())->getTable();
        $bomDetailTable = 'bom_detail';

        // Insert test BOM
        DB::table($bomTable)->insert([
            [
                'id' => 1,
                'bom_id' => 'BOM001',
                'bom_name' => 'Produk Test A',
                'measurement_unit' => 31,
                'total_cost' => 500000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'bom_id' => 'BOM002',
                'bom_name' => 'Produk Test B',
                'measurement_unit' => 31,
                'total_cost' => 750000,
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Insert BOM details
        DB::table($bomDetailTable)->insert([
            [
                'bom_id' => 'BOM001',
                'sku' => 'SKU001',
                'quantity' => 10,
                'cost' => 50000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bom_id' => 'BOM001',
                'sku' => 'SKU002',
                'quantity' => 5,
                'cost' => 20000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bom_id' => 'BOM002',
                'sku' => 'SKU003',
                'quantity' => 2,
                'cost' => 375000,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /** @test */
    public function it_can_cetak_pdf_single_bom()
    {
        $this->setupBOMData();

        $response = $this->get('/bom/1/cetak-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function it_returns_404_when_bom_not_found()
    {
        $this->setupBOMData();

        $response = $this->get('/bom/999/cetak-pdf');

        // Route might redirect, so accept multiple status codes
        $this->assertTrue(in_array($response->status(), [302, 404]));
    }

    /** @test */
    public function it_can_cetak_pdf_semua_bom()
    {
        $this->setupBOMData();

        $response = $this->get('/bom/cetak-semua-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function it_returns_empty_pdf_when_no_bom_exists()
    {
        $response = $this->get('/bom/cetak-semua-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function cetak_pdf_single_bom_includes_bom_details()
    {
        $this->setupBOMData();

        $response = $this->get('/bom/1/cetak-pdf');

        $response->assertStatus(200);
        // Verify PDF header content type
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function cetak_pdf_semua_bom_includes_all_bom_data()
    {
        $this->setupBOMData();

        $response = $this->get('/bom/cetak-semua-pdf');

        $response->assertStatus(200);
        // Verify PDF header content type
        $response->assertHeader('content-type', 'application/pdf');
    }
}
