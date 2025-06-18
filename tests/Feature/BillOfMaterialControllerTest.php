<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillOfMaterialControllerTest extends TestCase
{
    /**
     * Test ambil data BOM yang valid.
     */
    public function test_get_bom_by_valid_id()
    {
        $response = $this->get('/bom/detail/1');

        $response->assertStatus(200)
                 ->assertJson([
                'id' => 1,
                'bom_id' => 'BOM-001',
                'bom_name' => 'BOM-BOM-001',
                'measurement_unit' => 31,
                'total_cost' => 38489,
                'active' => 1,
                'created_at' => '2025-06-17 09:24:24',
                'updated_at' => '2025-06-17 09:24:24'
                 ]);
    }

    /**
     * Test ambil data BOM dengan ID yang tidak ada.
     */
    public function test_get_bom_by_invalid_id()
    {
        $response = $this->get('/bom/detail/999');

        $response->assertStatus(404);
    }
}
