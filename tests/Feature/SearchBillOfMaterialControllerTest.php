<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class SearchBillOfMaterialControllerTest extends TestCase
{
    use WithFaker;

    public function test_search_bill_of_material_endpoint_returns_expected_data()
    {
        $keyword = $this->faker->unique()->word;
        $bomName = 'TestBOM_' . $keyword;

        DB::table('bill_of_material')->insert([
            'bom_id' => 'BOM-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT), // ✅ FIX PANJANG
            'bom_name' => $bomName,
            'measurement_unit' => 1,
            'total_cost' => 9999,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get("/bill-of-material/search/{$keyword}");
        $response->dump();
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Data Bill of Material berhasil ditemukan.',
                 ])
                 ->assertJsonFragment([
                     'bom_name' => $bomName,
                 ])
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'current_page',
                         'data',
                         'first_page_url',
                         'from',
                         'last_page',
                         'last_page_url',
                         'links',
                         'next_page_url',
                         'path',
                         'per_page',
                         'prev_page_url',
                         'to',
                         'total',
                     ],
                 ]);
    }
}
