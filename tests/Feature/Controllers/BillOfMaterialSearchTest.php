<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\BillOfMaterial; 

class BillOfMaterialSearchTest extends TestCase
{
    use RefreshDatabase; 

    /** @test */
    public function it_can_search_bill_of_material_by_keyword()
    {
        $tableName = (new BillOfMaterial())->getTable();

        DB::table($tableName)->insert([
            [
                'bom_id' => 'BOM001', 
                'bom_name' => 'Laptop Gaming', 
                'measurement_unit' => 1,
                'total_cost' => 10000000,
                'active' => 1,
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'bom_id' => 'BOM002', 
                'bom_name' => 'Meja Kantor', 
                'measurement_unit' => 1,
                'total_cost' => 500000,
                'active' => 1,
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        $response = $this->get('/bill-of-material/search/Laptop');

        $response->assertStatus(200);
        $response->assertSee('Laptop Gaming');
        $response->assertDontSee('Meja Kantor');
    }

    /** @test */
    public function it_returns_empty_when_keyword_not_found()
    {
        $tableName = (new BillOfMaterial())->getTable();

        DB::table($tableName)->insert([
            [
                'bom_id' => 'BOM001', 
                'bom_name' => 'Laptop Gaming', 
                'measurement_unit' => 1,
                'total_cost' => 10000000,
                'active' => 1,
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        $response = $this->get('/bill-of-material/search/Mobil');

        $response->assertStatus(200);
        $response->assertDontSee('Laptop Gaming');
    }
}