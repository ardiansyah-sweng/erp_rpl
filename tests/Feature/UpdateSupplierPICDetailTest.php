<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\SupplierPIC;
use Tests\TestCase;
use Faker\Factory as Faker;

class UpdateSupplierPICDetailTest extends TestCase
{
    public function test_controllerUpdateSupplierPIC(): void
    {
        $faker = Faker::create();

        /**
         * Ambil satu SupplierPIC acak; jika tidak ada, buat data contoh
         * --------------------------------------------------------------
         *  - supplier_pic.id         : bigint auto‑increment (integer)
         *  - supplier_pic.supplier_id: char(6) → harus match supplier.supplier_id
         */
        $pic = SupplierPIC::inRandomOrder()->first();

        if (!$pic) {
            /* Pastikan ada supplier dengan supplier_id (char) */
            $supplier = Supplier::first();
            if (!$supplier) {
                $supplier = Supplier::create([
                    'supplier_id'  => 'SUP' . str_pad($faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
                    'company_name' => $faker->company,
                ]);
            }

            $pic = SupplierPIC::create([
                'supplier_id'   => $supplier->supplier_id, // char(6)
                'name'          => $faker->name,
                'phone_number'  => $faker->numerify('08##########'),
                'email'         => $faker->unique()->safeEmail,
                'assigned_date' => now()->subDays(10)->toDateString(),
            ]);
        }

        dump('Before Update:', $pic->toArray());

        // Data baru yang ingin di‑update (tidak mengirim field id!)
        $newData = [
            'supplier_id'   => $pic->supplier_id,
            'name'          => $faker->name,
            'phone_number'  => $faker->numerify('08##########'),
            'email'         => $faker->unique()->safeEmail,
            'assigned_date' => now()->toDateString(),
        ];

        // Hit endpoint POST /supplier-pic/update/{id}
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
