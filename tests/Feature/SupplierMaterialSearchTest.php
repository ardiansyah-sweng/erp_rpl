<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SupplierMaterial;

class SupplierMaterialSearchTest extends TestCase
{
  
    public function test_search_supplier_material_by_keyword()
    {
        $material1 = new SupplierMaterial();
        $material1->supplier_id = 'SUP007';
        $material1->company_name = 'PD United Shipping Sejahteraraya';
        $material1->product_id = 'P001-et';
        $material1->product_name = 'Plus 779g et';
        $material1->base_price = 78049;
        $material1->created_at = now();
        $material1->updated_at = now();
        $material1->save();

        $material2 = new SupplierMaterial();
        $material2->supplier_id = 'SUP014';
        $material2->company_name = 'PT Borneo Wattle Shine Steady';
        $material2->product_id = 'P001-et';
        $material2->product_name = 'Plus 779g et';
        $material2->base_price = 84941;
        $material2->created_at = now();
        $material2->updated_at = now();
        $material2->save();

        // Kirim request pencarian keyword
        $response = $this->getJson('/supplier/material/search?keyword=Sejahtera');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'company_name' => 'PD United Shipping Sejahteraraya'
        ]);
        $response->assertJsonMissing([
            'company_name' => 'PT Borneo Wattle Shine Steady'
        ]);
    }

    public function test_search_supplier_material_with_no_match()
    {
        // Pencarian dengan keyword yang tidak cocok
        $response = $this->getJson('/supplier/material/search?keyword=TidakAdaData');

        $response->assertStatus(200);
        $response->assertJsonCount(0); 
    }
}
