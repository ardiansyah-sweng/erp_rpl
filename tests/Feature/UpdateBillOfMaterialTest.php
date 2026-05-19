<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillOfMaterial;

class UpdateBillOfMaterialTest extends TestCase
{
    public function test_update_existing_bill_of_material_from_database()
    {
        $bom = BillOfMaterial::find(2);

        if (!$bom) {
            $this->markTestSkipped('No Bill of Material records found in the database.');
        }

        $updateData = [
            'active'      => 0,
        ];

        $response = $this->put('/bill-of-material/' . $bom->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Bill of Material updated successfully.',
                'data' => [
                    'id' => $bom->id,
                    'active' => 0,
                ]
            ]);

        $this->assertDatabaseHas('bill_of_material', [
            'id' => $bom->id,
            'active' => 0,
        ]);
    }
}
