<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class BillOfMaterialControllerTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->get('/bill-of-material', ['Accept' => 'application/json']);
        
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