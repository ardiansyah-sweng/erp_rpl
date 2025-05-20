<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_404_if_supplier_not_found()
    {
        $response = $this->putJson('/supplier/update/SUP999', [
            'company_name'  => 'PT Baru',
            'address'       => 'Alamat Baru',
            'phone_number'  => '08123456789',
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Data Supplier Tidak Tersedia'
                 ]);
    }

    public function test_it_validates_required_fields()
    {
        $supplier = Supplier::create([
            'supplier_id'   => 'SUP100',
            'company_name'  => 'PT Lama',
            'address'       => 'Alamat Lama',
            'phone_number'  => '080000000',
            'bank_account'  => '1234567890',
        ]);

        $response = $this->putJson("/supplier/update/{$supplier->supplier_id}", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['company_name', 'address', 'phone_number']);
    }

    public function test_it_updates_supplier_data_successfully()
    {
        $supplier = Supplier::create([
            'supplier_id'   => 'SUP200',
            'company_name'  => 'PT Lama',
            'address'       => 'Alamat Lama',
            'phone_number'  => '080000000',
            'bank_account'  => '1234567890',
        ]);

        $payload = [
            'company_name'  => 'PT Baru',
            'address'       => 'Alamat Baru',
            'phone_number'  => '0899123456',
        ];

        $response = $this->putJson("/supplier/update/{$supplier->supplier_id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message'       => 'Data Supplier berhasil diperbarui',
                     'supplier_id'   => 'SUP200',
                     'company_name'  => 'PT Baru',
                     'address'       => 'Alamat Baru',
                     'phone_number'  => '0899123456',
                 ]);

        $this->assertDatabaseHas(config('db_constants.table.supplier'), [
            'supplier_id'   => 'SUP200',
            'company_name'  => 'PT Baru',
        ]);
    }
}
