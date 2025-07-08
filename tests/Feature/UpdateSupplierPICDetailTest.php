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

        $pic = SupplierPic::inRandomOrder()->first();

        if (!$pic)
        {
            $this->markTestSkipped('Tidak ada data SupplierPic di database untuk diuji.');
        }

        $newData = [
            'supplier_id'   => $pic->supplier_id,
            'name'          => $faker->name,
            'phone_number'  => $faker->numerify('08##########'),
            'email'         => $faker->unique()->safeEmail,
            'assigned_date' => now()->toDateString(),
        ];

        $response = $this->post('/supplier-pic/update/' . $pic->id, $newData);

        dump('After Update Response:', $response->json());

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $newData['name']]);

        $this->assertDatabaseHas('supplier_pic', [
            'id'    => $pic->id,
            'email' => $newData['email'],
        ]);
    }
}
