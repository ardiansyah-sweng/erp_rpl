<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPIC; // Pastikan model ini ada
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddSupplierPICTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sukses menambahkan PIC ke Supplier tertentu.
     */
    public function test_add_supplier_pic_success(): void
    {
        // 1. Buat data Supplier sebagai induk
        // Gunakan field lengkap untuk menghindari error 'default value'
        $supplier = Supplier::create([
            'supplier_id'   => 'S1', 
            'company_name'  => 'PT Test Supplier',
            'name'          => 'Admin Supplier',
            'address'       => 'Jl. Contoh No. 1',
            'telephone'     => '0812345',
            'bank_account'  => '998877',
        ]);

        // 2. Data PIC baru yang akan dikirim
        $payload = [
            'pic_name'  => 'Budi PIC',
            'email'     => 'budi@example.com',
            'phone'     => '08556677',
            'position'  => 'Manager',
        ];

        // 3. Aksi: Kirim permintaan POST ke endpoint
        // Pastikan route di api.php: /api/supplier-pic/{supplierID}
        $response = $this->postJson("/api/supplier-pic/{$supplier->supplier_id}", $payload);

        // 4. Verifikasi status dan database
        $response->assertStatus(201); // 201 Created
        $this->assertDatabaseHas('supplier_pics', [
            'supplier_id' => 'S1',
            'name'        => 'Budi PIC'
        ]);
    }

    /**
     * Test gagal tambah PIC jika Supplier tidak ditemukan.
     */
    public function test_add_supplier_pic_not_found(): void
    {
        $payload = ['pic_name' => 'Data Tanpa Supplier'];

        $response = $this->postJson("/api/supplier-pic/KODE-SALAH", $payload);

        $response->assertStatus(404);
    }
}