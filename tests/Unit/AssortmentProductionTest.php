<?php

namespace Tests\Unit;

use App\Models\AssortmentProduction;
use Tests\TestCase;


class AssortmentProductionTest extends TestCase
{
    public function testGetProductionDetail()
    {
        // Simulasi data yang akan diuji
        $productionNumber = 'PROD-001';
        $response = AssortmentProduction::getProductionDetail($productionNumber);
        $data = $response->getData();

        dump($data);

        $this->assertEquals($productionNumber, $data->header->production_number);
        $this->assertNotEmpty($data->details);
    }
}
