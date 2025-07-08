<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BillOfMaterial;

class SearchOfBillMaterialTest extends TestCase
{
    public function test_search_bill_of_material(): void
    {
        $sample = BillOfMaterial::inRandomOrder()->first();
        $this->assertNotNull($sample, 'Data bill_of_materials kosong, tidak bisa dites.');
        $keyword = substr($sample->bom_name, 0, 3); 
        $result = BillOfMaterial::SearchOfBillMaterial($keyword);
        dump($result);
        $this->assertTrue($result->count() > 0, 'Tidak ada hasil yang ditemukan dengan keyword: ' . $keyword);
    }
}
