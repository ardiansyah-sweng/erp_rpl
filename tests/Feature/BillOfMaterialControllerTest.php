<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class BillOfMaterialControllerTest extends TestCase
{
    // Optional: agar tidak perlu login/middleware saat testing
    use WithoutMiddleware;
    use RefreshDatabase;

    /** @test */
    public function it_deletes_bill_of_material_by_id()
    {
        // 1. Hapus data dengan bom_id 'BOM-999' jika ada (hindari duplikat)
        DB::table('bill_of_material')->where('bom_id', 'BOM-999')->delete();

        // 2. Insert data dummy
        $id = DB::table('bill_of_material')->insertGetId([
            'bom_id'            => 'BOM-999',
            'bom_name'          => 'BOM-BOM-999',
            'measurement_unit'  => 31,
            'total_cost'        => 9999,
            'active'            => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 3. Panggil endpoint DELETE
        $response = $this->delete('/bill-of-material/' . $id);

        // 4. Cek response & data terhapus
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Bill of Material deleted successfully.']);
        $this->assertDatabaseMissing('bill_of_material', ['id' => $id]);
    }

    /** @test */
    public function it_returns_404_if_bill_of_material_not_found()
    {
        // Pastikan ID tidak ada di database
        $id = 999999;

        $response = $this->delete('/bill-of-material/' . $id);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Bill of Material not found.']);
    }


   /** @test */
    public function test_get_bom_detail_with_join()
    {
        // Insert Bill of Material
        $bomId = DB::table('bill_of_material')->insertGetId([
            'bom_id' => 'BOM-123',
            'bom_name' => 'Test BOM',
            'measurement_unit' => 1,
            'total_cost' => 5000,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert 2 bom_detail rows
        DB::table('bom_detail')->insert([
            [
                'bom_id' => 'BOM-123',
                'sku' => 'SKU-001',
                'quantity' => 10,
                'cost' => 2500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 'BOM-123',
                'sku' => 'SKU-002',
                'quantity' => 5,
                'cost' => 2500,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Hit endpoint
        $response = $this->get("/bill-of-material/{$bomId}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'id',
            'bom_id',
            'bom_name',
            'measurement_unit',
            'total_cost',
            'active',
            'created_at',
            'updated_at',
            'details' => [
                '*' => [
                    'id',
                    'bom_id',
                    'sku',
                    'quantity',
                    'cost',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $this->assertCount(2, $response->json('details'));
    }


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