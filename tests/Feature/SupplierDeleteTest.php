<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_supplier()
    {
        // 1. Buat data palsu dengan kolom yang BENAR sesuai database kamu
        $supplier = Supplier::create([
            'supplier_id' => '999',
            'company_name' => 'PT Test Hapus',
            'address' => 'Alamat Palsu',
            'telephone' => '0812345678', // Ganti phone_number jadi telephone
            'bank_account' => '12345',
        ]);

        // 2. Jalankan perintah hapus
        $response = $this->delete(route('supplier.destroy', $supplier->supplier_id));

        // 3. Pastikan melakukan redirect (Status 302)
        $response->assertStatus(302);

        // 4. Pastikan datanya BENAR-BENAR hilang dari database
        $this->assertDatabaseMissing('suppliers', [
            'supplier_id' => '999'
        ]);
    }
}