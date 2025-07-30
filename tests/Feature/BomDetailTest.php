<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillOfMaterial;

class BomDetailTest extends TestCase
{
    public function testGetBomDetailFromExistingId()
    {
        $bom = BillOfMaterial::find(4);
        $this->assertNotNull($bom, 'No BOM record found in database.');

        $response = $this->get("/bomdetail/{$bom->id}");

        dd($response->json());

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
    }
}
