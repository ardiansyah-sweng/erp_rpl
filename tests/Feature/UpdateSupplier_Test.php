<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use Faker\Factory as Faker;

class UpdateSupplier_Test extends TestCase
{
    use RefreshDatabase;

    /**
     * Tes untuk memastikan fungsi update di Model bekerja.
     * Sesuai tugas Nomor 16 (SupplierModel).
     */
    public function test_modelUpdate(): void
    {
        $faker = Faker::create();

        // 1. Buat data supplier awal dengan ID pendek (maks 10 karakter)
        // Perbaikan error: Data too long
        $supplier = Supplier::create([
            'supplier_id'  => 'SUP' . $faker->unique()->numberBetween(100, 999),
            'company_name' => $faker->company,
            'address'      => $faker->address,
            'phone_number' => $faker->phoneNumber,
            'bank_account' => $faker->bankAccountNumber,
        ]);

        $newData = [
            'company_name' => 'PT Update Sukses',
            'address'      => 'Jl. Ringroad Utara, Sleman',
            'phone_number' => '08123456789',
            'bank_account' => '987654321',
        ];

        // 2. Panggil fungsi update dari Model
        $updateSupplier = Supplier::updateSupplier($supplier->supplier_id, $newData);

        // 3. Pastikan data berhasil diupdate
        $this->assertNotNull($updateSupplier);
        $this->assertEquals($newData['company_name'], $updateSupplier->company_name);
    }

    /**
     * Tes untuk memastikan fungsi update di Controller bekerja.
     * Sesuai tugas Nomor 17 (SupplierController).
     */
    public function test_controllerUpdate(): void
    {
        $faker = Faker::create();

        $supplier = Supplier::create([
            'supplier_id'  => 'SUP' . $faker->unique()->numberBetween(100, 999),
            'company_name' => $faker->company,
            'address'      => $faker->address,
            'phone_number' => $faker->phoneNumber,
            'bank_account' => $faker->bankAccountNumber,
        ]);

        $newData = [
            'company_name' => 'Nama Perusahaan Baru',
            'address'      => $faker->address,
            'phone_number' => '08998877665',
            'bank_account' => '1122334455',
        ];

        // 1. Jalankan request PUT ke route update
        $response = $this->put('/supplier/update/' . $supplier->supplier_id, $newData);

        // 2. Pastikan status 302 (Redirect) menandakan update di Controller berhasil
        $response->assertStatus(302);

        // 3. Pastikan database mencatat perubahan pada tabel 'supplier'
        // Perbaikan error: Table 'laravel.suppliers' doesn't exist
        $this->assertDatabaseHas('supplier', [
            'supplier_id'  => $supplier->supplier_id,
            'company_name' => $newData['company_name'],
        ]);
    }
}