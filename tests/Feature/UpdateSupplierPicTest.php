<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SupplierPic;
use Faker\Factory as Faker;

class UpdateSupplierPicTest extends TestCase
{
    use WithFaker;

    public function test_modelUpdateSupplierPic(): void
    {
        $faker = Faker::create(); // Inisialisasi Faker

        // Ambil satu data acak dari tabel SupplierPic
        $supplierPic = SupplierPic::inRandomOrder()->first();

        // Dump data sebelum update
        dump("Before Update:", $supplierPic);

        // Data baru yang akan diupdate
        $newData = [
            'name' => $faker->name,
            'phone_number' => $faker->phoneNumber,
            'email' => $faker->unique()->safeEmail,
            'assigned_date' => $faker->date(), // Format: YYYY-MM-DD
        ];

        // Lakukan update
        $supplierPic->update($newData);

        // Ambil data terbaru dari DB
        $updatedSupplierPic = $supplierPic->fresh();

        // Dump hasil setelah update
        dump("After Update:", $updatedSupplierPic);

        // Assert bahwa update berhasil
        $this->assertEquals($newData['name'], $updatedSupplierPic->name);
        $this->assertEquals($newData['phone_number'], $updatedSupplierPic->phone_number);
        $this->assertEquals($newData['email'], $updatedSupplierPic->email);
        $this->assertEquals($newData['assigned_date'], $updatedSupplierPic->assigned_date);
    }
}
