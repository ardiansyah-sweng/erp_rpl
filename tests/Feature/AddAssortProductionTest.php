<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortProduction;
use Illuminate\Foundation\Testing\WithFaker;

class AddAssortProductionTest extends TestCase
{
    use WithFaker;

    public function test_user_can_add_assort_production()
    {
        $formData = [
            'production_number' => 'PROD-500',
            'sku'               => 'SKU-' . $this->faker->word,
            'branch_id'         => 1,
            'rm_whouse_id'      => 2,
            'fg_whouse_id'      => 4,
            'production_date'   => now()->format('Y-m-d H:i:s'),
            'finished_date'     => null,
            'description'       => 'Test add production',
        ];

        $response = $this->post('/assort-production/add', $formData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Production record added successfully.',
                'data' => [
                    'production_number' => $formData['production_number'],
                    'sku'               => $formData['sku'],
                    'branch_id'         => $formData['branch_id'],
                    'rm_whouse_id'      => $formData['rm_whouse_id'],
                    'fg_whouse_id'      => $formData['fg_whouse_id'],
                    'description'       => $formData['description'],
                ]
            ]);

        $this->assertDatabaseHas('assortment_production', [
            'production_number' => $formData['production_number'],
            'sku'               => $formData['sku'],
        ]);
    }
}
