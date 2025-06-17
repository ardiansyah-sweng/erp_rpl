<?php

namespace Tests\Feature;

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
                     'sku' => 'P006-ex',
                     'quantity' => 9,
                     'cost' => 2779,
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
