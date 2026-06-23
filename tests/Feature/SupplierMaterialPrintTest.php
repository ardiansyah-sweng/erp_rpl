<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use App\Models\Supplier;

class SupplierMaterialPrintTest extends TestCase
{

    public function test_cetak_pdf_seluruh_material_route_exists()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya Indonesia',
            'product_id' => 'PROD-001',
            'product_name' => 'Plastik HDPE Grade A',
            'base_price' => 50000,
        ]);

        $response = $this->get('/supplier-material/cetak-pdf');
        
        $response->assertStatus(200);
    }

    public function test_cetak_pdf_seluruh_material_returns_pdf_response()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya Indonesia',
            'product_id' => 'PROD-001',
            'product_name' => 'Plastik HDPE Grade A',
            'base_price' => 50000,
        ]);

        $response = $this->get('/supplier-material/cetak-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_cetak_pdf_seluruh_material_with_multiple_suppliers()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya Indonesia',
            'product_id' => 'PROD-001',
            'product_name' => 'Plastik HDPE Grade A',
            'base_price' => 50000,
        ]);

        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya Indonesia',
            'product_id' => 'PROD-002',
            'product_name' => 'Plastik LDPE Grade B',
            'base_price' => 75000,
        ]);

        SupplierMaterial::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'PT Sinar Mulia',
            'product_id' => 'PROD-003',
            'product_name' => 'Kawat Tembaga Murni',
            'base_price' => 100000,
        ]);

        $response = $this->get('/supplier-material/cetak-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_cetak_pdf_seluruh_material_always_has_data()
    {
        $response = $this->get('/supplier-material/cetak-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_cetak_pdf_seluruh_material_filename_contains_date()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya Indonesia',
            'product_id' => 'PROD-001',
            'product_name' => 'Plastik HDPE Grade A',
            'base_price' => 50000,
        ]);

        $response = $this->get('/supplier-material/cetak-pdf');

        $response->assertStatus(200);
        $this->assertStringContainsString('data_seluruh_supplier_material_', $response->headers->get('Content-Disposition'));
    }

    public function test_cetak_pdf_single_supplier_still_works()
    {
        SupplierMaterial::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Maju Jaya Indonesia',
            'product_id' => 'PROD-001',
            'product_name' => 'Plastik HDPE Grade A',
            'base_price' => 50000,
        ]);

        $response = $this->get('/supplier/SUP001/cetak-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_cetak_pdf_single_supplier_with_no_data_returns_error()
    {
        $response = $this->get('/supplier/NONEXISTENT/cetak-pdf');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Data supplier tidak ditemukan.');
    }
}
