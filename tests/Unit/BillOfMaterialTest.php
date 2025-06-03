<?php

namespace Tests\Unit;

use Tests\TestCase; 
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillOfMaterialTest extends TestCase
{

    public function testAddBillOfMaterialCreatesBillOfMaterial()
    {
        config(['db_constants.table.bom' => 'bill_of_material']);
        config(['db_constants.column.bom' => [
            'bom_id',
            'bom_name', 
            'measurement_unit',
            'total_cost',
            'active'
        ]]);

        $data = [
            'bom_id' => '10',
            'bom_name' => 'Test BOM',
            'measurement_unit' => '31',
            'total_cost' => '1000',
            'active' => '1',
        ];

        $result = BillOfMaterial::addBillOfMaterial($data);
        
        $this->assertInstanceOf(BillOfMaterial::class, $result);
        $this->assertEquals($data['bom_name'], $result->bom_name);
        $this->assertEquals($data['bom_id'], $result->bom_id);
    }

    public function testAddBillOfMaterialHandlesEmptyData()
    {
        config(['db_constants.table.bom' => 'bill_of_materials']);
        config(['db_constants.column.bom' => [
            'bom_id',
            'bom_name',
            'measurement_unit', 
            'total_cost',
            'active'
        ]]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data tidak boleh kosong.');
        
        BillOfMaterial::addBillOfMaterial([]);
    }
}