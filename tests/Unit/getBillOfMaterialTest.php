<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;

class getBillOfMaterialTest extends TestCase
{

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


        BillOfMaterial::factory()->count(15)->create();

        $result = BillOfMaterial::getBillOfMaterial();

        $this->assertNotEmpty($result);
        $this->assertTrue($result->total() >= 10);
        $this->assertEquals(10, $result->count());
    }
}
