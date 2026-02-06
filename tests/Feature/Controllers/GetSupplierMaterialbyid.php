<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetSupplierMaterialbyid extends TestCase
{
    use RefreshDatabase;

    public function test_get_supplier_material_by_id_success()
    {
        // 1. Pastikan semua field wajib terisi dengan data yang sangat ringkas
        $supplier = Supplier::create([
            'supplier_id'   => 'S1', // ID singkat agar tidak 'Data too long'
            'company_name'  => 'PT Baja',
            'name'          => 'Supplier Utama',
            'address'       => 'Jl Industri',
            'telephone'     => '021123',
            'bank_account'  => '12345',
        ]);

        // 2. Aksi: Gunakan route '/api/suppliers/{id}'
        $response = $this->get("/api/suppliers/{$supplier->id}");

        
        $response->assertStatus(200);
    }

    public function test_get_supplier_material_not_found()
    {
        // Mencari ID yang pasti tidak ada - akan return view dengan null data
        $response = $this->get("/api/suppliers/999999");
        // Controller mereturn view, bukan JSON 404
        $response->assertStatus(200);
    }
}