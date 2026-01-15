<?php

namespace tests\Unit\Models;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;

class getBillOfMaterialTest extends TestCase
{
    use RefreshDatabase;

    public function testGetBillOfMaterialReturnsPaginatedData()
    {
        config(['db_constants.table.bom' => 'bill_of_material']);
        config(['db_constants.column.bom' => [
            'bom_id' => 'bom_id',
            'bom_name' => 'bom_name',
            'measurement_unit' => 'measurement_unit',
            'total_cost' => 'total_cost',
            'active' => 'active'
        ]]);

        // Create 11 sample records so pagination returns 10 on the first page
        for ($i = 1; $i <= 11; $i++) {
            BillOfMaterial::create([
                'bom_id' => 'BOM' . $i,
                'bom_name' => 'Sample BOM ' . $i,
                'measurement_unit' => 1,
                'total_cost' => 100 * $i,
                'active' => 1,
            ]);
        }

        $result = BillOfMaterial::getBillOfMaterial();

        $this->assertNotEmpty($result);
        $this->assertTrue($result->total() >= 10);
        $this->assertEquals(10, $result->count());
    }
}