<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class AddProductionControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
        $this->faker->unique(true); // Reset uniqueness
    }

    public function testAddProductionWithFaker()
    {
                $data = [
            'in_production' => 0,
            'production_number' => $this->faker->unique()->bothify('P#######'), // max 9 chars
            'sku' => strtoupper($this->faker->bothify('SKU-##')), // sesuaikan panjang juga jika SKU dibatasi
            'branch_id' => 53,
            'rm_whouse_id' => 99,
            'fg_whouse_id' => 18,
            'production_date' => now()->subDays(2)->format('Y-m-d H:i:s'),
            'finished_date' => now()->format('Y-m-d H:i:s'),
            'description' => $this->faker->sentence(),
        ];

        $response = $this->postJson('/assort-production', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Data produksi berhasil ditambahkan.',
                 ]);

        $this->assertDatabaseHas('assortment_production', [
            'production_number' => $data['production_number']
        ]);
    }
}