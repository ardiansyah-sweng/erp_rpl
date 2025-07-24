<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing;
use Tests\TestCase;
use App\Models\BillOfMaterial;

class UpdateBillOfMaterialModelTest extends TestCase
{

    /** @test */
    public function it_updates_existing_bill_of_material()
    {
        // Arrange: buat entri dengan id auto-increment
        $bom = BillOfMaterial::create([
            'bom_id' => 'BOM-999',
            'bom_name' => 'BOM-BOM-001',
            'measurement_unit' => 31,
            'total_cost' => 50000,
            'active' => 1,
            'created_at' => '2025-07-01 11:55:48',
            'updated_at' => '2025-07-01 11:55:48'
        ]);

        $updateData = [
            'bom_name' => 'Updated BOM',
            'total_cost' => 75000,
        ];

        // Act: gunakan id (PK) saat memanggil method
        $updatedBOM = BillOfMaterial::updateBillOfMaterial($bom->id, $updateData);

        // Assert
        $this->assertNotNull($updatedBOM);
        $this->assertEquals('Updated BOM', $updatedBOM->bom_name);
        $this->assertEquals(75000, $updatedBOM->total_cost);
    }

    /** @test */
    public function it_returns_null_if_bill_of_material_not_found()
    {
        $updateData = [
            'bom_name' => 'No BOM',
            'total_cost' => 0,
        ];

        // id 999 tidak ada
        $result = BillOfMaterial::updateBillOfMaterial(999, $updateData);

        $this->assertNull($result);
    }
}