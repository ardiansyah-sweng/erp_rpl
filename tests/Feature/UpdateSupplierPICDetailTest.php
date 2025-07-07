<?php

namespace Tests\Feature;

use App\Models\SupplierPic;
use Tests\TestCase;
use Faker\Factory as Faker;

class UpdateSupplierPICDetailTest extends TestCase
{
    public function test_controller_update_supplier_pic(): void
    {
        $faker = Faker::create();

        // 1. Ambil satu data SupplierPic secara acak dari database
        $pic = SupplierPic::inRandomOrder()->first();

        // 2. Jika tidak ditemukan, skip test dengan pesan
        if (!$pic) {
            $this->markTestSkipped('Tidak ada data SupplierPic di database untuk diuji.');
        }

        // 3. Siapkan data update
        $newData = [
            'supplier_id'   => $pic->supplier_id, // harus valid di tabel supplier
            'name'          => $faker->name,
            'phone_number'  => $faker->numerify('08##########'),
            'email'         => $faker->unique()->safeEmail,
            'assigned_date' => now()->toDateString(),
        ];

        // 4. Kirim request ke endpoint update
        $response = $this->post('/supplier-pic/update/' . $pic->id, $newData);

        // 5. Debug respons (opsional)
        dump('After Update Response:', $response->json());

        // 6. Cek response sukses dan data terupdate
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $newData['name']]);

        $this->assertDatabaseHas('supplier_pic', [
            'id'    => $pic->id,
            'email' => $newData['email'],
        ]);
    }
}
