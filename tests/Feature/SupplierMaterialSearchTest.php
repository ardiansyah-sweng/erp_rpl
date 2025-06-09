<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierMaterialSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_add_supplier_material_with_valid_data()
    {
        $response = $this->post('/supplier-material/add', [
            'supplier_id'  => 'SUP002',
            'company_name' => 'PT Murni Subsea Ladangbaja Bukaka',
            'product_id'   => 'P001-ut',
            'product_name' => 'Peach Dalam ut',
            'base_price'     => 57728,
            'created_at'   => now()->toDateTimeString(),
            'updated_at'   => now()->toDateTimeString(),
        ]);

        $response->assertRedirect(); // karena redirect()->back()
        $response->assertSessionHas('success', 'Data supplier product berhasil divalidasi!');
    }

    /** @test */
    public function test_add_supplier_material_with_missing_required_fields_should_fail()
    {
        $response = $this->post('/supplier-material/add', [
            // intentionally missing required fields
        ]);

        $response->assertSessionHasErrors([
            'supplier_id',
            'company_name',
            'product_id',
            'product_name',
            'base_price',
        ]);
    }
}
