<?php

namespace Tests\Feature;
use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BillOfMaterialControllerTest  extends TestCase
{
    public function test_update_bill_of_material(): void
    {
            $faker = \Faker\Factory::create(); // Inisialisasi Faker

            // Ambil salah satu data Bill of Material secara acak dari database
            $bom = \App\Models\BillOfMaterial::inRandomOrder()->first();

            // Dump jika Anda ingin melihat isi data yang dipilih
            dump($bom);

            // Siapkan data baru untuk update
            $updatedData = [
                'bom_name' => $faker->unique()->word,
                'measurement_unit' => $faker->randomElement(['kg', 'meter', 'liter', 'pcs']),
                'total_cost' => $faker->randomFloat(2, 100, 10000),
                'active' => $faker->boolean,
            ];

            $response = $this->post('/billofmaterial/update/' . $bom->bom_id, $updatedData);
            $response->assertStatus(302);

            // Cek apakah data di database sudah berubah
            $this->assertDatabaseHas('bill_of_materials', [
                'bom_id' => $bom->bom_id,
                'bom_name' => $updatedData['bom_name'],
                'measurement_unit' => $updatedData['measurement_unit'],
                'total_cost' => $updatedData['total_cost'],
                'active' => $updatedData['active'],
            ]);
    }

}
