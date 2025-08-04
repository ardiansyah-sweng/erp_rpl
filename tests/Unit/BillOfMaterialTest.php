<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\BillOfMaterial;

class BillOfMaterialTest extends TestCase
{
    /** @test */
    public function it_counts_items_in_existing_boms()
    {
        
        $bomIds = ['BOM-001', 'BOM-002', 'BOM-003'];

        $counts = [];

        foreach ($bomIds as $id) {
            
            $bom = BillOfMaterial::where('bom_id', $id)->first();
            $this->assertNotNull($bom, "Data BOM dengan ID $id tidak ditemukan.");

            
            $count = BillOfMaterial::countItemInBom($id);
            $counts[$id] = $count;
        }

        
        dump($counts);

        
        $this->assertIsArray($counts);
    }
}
