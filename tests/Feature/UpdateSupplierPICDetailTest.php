<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\SupplierPIC;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class UpdateSupplierPICDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_controllerUpdateSupplierPIC(): void
    {
        $faker = Faker::create(); // Inisialisasi Faker

        // Ambil satu SupplierPIC secara acak
        $pic = SupplierPIC::inRandomOrder()->first();

        // Tampilkan data awal
        dump("Before Update:", $pic->toArray());

        // Siapkan data baru untuk update
        $newData = [
            'id'            => $pic->supplier_id,
            'name'          => $faker->name,
            'phone_number'  => $faker->numerify('08##########'),
            'email'         => $faker->unique()->safeEmail,
            'assigned_date' => now()->toDateString(),
        ];

        // Kirim POST request ke endpoint update sesuai route Anda
        $response = $this->post('/supplier-pic/update/' . $pic->id, $newData);

        // Tampilkan response
        dump("After Update Response:", $response->json());

        // Cek respons sukses dan hasil update
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $newData['name']]);

        $this->assertDatabaseHas('supplier_pics', [
            'id'    => $pic->id,
            'email' => $newData['email'],
        ]);
    }
}
