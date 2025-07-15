<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Faker\Factory as Faker;
use App\Models\BillOfMaterial;

class BillOfMaterialControllerTest extends TestCase
{
    use WithoutMiddleware; // Agar bisa bypass middleware seperti auth
    use RefreshDatabase, WithFaker;

    /** @test */
    public function test_update_bill_of_material(): void
    {
        $faker = Faker::create();

        // Insert data dummy untuk diupdate
        $bom = BillOfMaterial::create([
            'bom_name' => 'OldName-' . $faker->unique()->word,
            'measurement_unit' => 'pcs',
            'total_cost' => 5000,
            'active' => true,
        ]);

        // Data update
        $updatedData = [
            'bom_name' => $faker->unique()->word,
            'measurement_unit' => $faker->randomElement(['kg', 'meter', 'liter', 'pcs']),
            'total_cost' => $faker->randomFloat(2, 100, 10000),
            'active' => $faker->boolean,
        ];

        $response = $this->post('/billofmaterial/update/' . $bom->bom_id, $updatedData);
        $response->assertStatus(302); // Redirect sukses

        $this->assertDatabaseHas('bill_of_materials', [
            'bom_id' => $bom->bom_id,
            'bom_name' => $updatedData['bom_name'],
            'measurement_unit' => $updatedData['measurement_unit'],
            'total_cost' => $updatedData['total_cost'],
            'active' => $updatedData['active'],
        ]);
    }

    /** @test */
    public function it_deletes_bill_of_material_by_id()
    {
        // Insert data dummy
        $bom = BillOfMaterial::create([
            'bom_name' => 'BOM-999',
            'measurement_unit' => 'kg',
            'total_cost' => 9999,
            'active' => true,
        ]);

        $response = $this->delete('/bill-of-material/' . $bom->bom_id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Bill of Material deleted successfully.']);

        $this->assertDatabaseMissing('bill_of_materials', ['bom_id' => $bom->bom_id]);
    }

    /** @test */
    public function it_returns_404_if_bill_of_material_not_found()
    {
        $nonExistentId = 999999;

        $response = $this->delete('/bill-of-material/' . $nonExistentId);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Bill of Material not found.']);
    }

    /** @test */
    public function test_get_bill_of_material_returns_paginated_data()
    {
        for ($i = 0; $i < 15; $i++) {
            DB::table('bill_of_material')->insert([
                'bom_id' => 'BOM-' . $i,
                'bom_name' => 'BOM Name ' . $i,
                'measurement_unit' => 1,
                'total_cost' => 1000 + $i,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Panggil endpoint
        $response = $this->get('/bill-of-material');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'bom_id',
                    'bom_name',
                    'measurement_unit',
                    'total_cost',
                    'active',
                    'created_at',
                    'updated_at'
                ]
            ],
            'current_page',
            'last_page',
            'per_page',
            'total'
        ]);

        $this->assertCount(10, $response->json('data'));
    }
    
}
