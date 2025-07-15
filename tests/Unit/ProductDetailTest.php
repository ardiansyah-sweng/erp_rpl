<?php

namespace Tests\Unit;

use App\Models\AssortmentProduction;
use App\Models\AssortmentProductionDetail;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    public function test_data()
    {
         $this->assertDatabaseHas('assortment_production_detail', [
            'production_number' => 'PROD-001',
        ]);

        $response = AssortmentProduction::getProductionDetail('PROD-001');
        $data = $response->getData();

        $this->assertEquals('PROD-001', $data->header->production_number);
        $this->assertNotEmpty($data->details);
    }
}
