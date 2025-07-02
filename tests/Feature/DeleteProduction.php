<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortmentProduction;

class DeleteProduction extends TestCase
{
    public function test_can_delete_production_when_in_production_is_0()
    {
        $this->assertDatabaseHas('assortment_production', [
            'production_number' => 'PROD-001',
            'in_production' => 0,
        ]);

        $response = AssortmentProduction::deleteProduction('PROD-001');
        dump($response->getData());

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Production deleted successfully', $response->getData()->message);
        $this->assertDatabaseMissing('assortment_production', [
            'production_number' => 'PROD-001'
        ]);
    }

    public function test_cannot_delete_production_when_in_production_is_1()
    {
        $this->assertDatabaseHas('assortment_production', [
            'production_number' => 'PROD-004',
            'in_production' => 1,
        ]);

        $response = AssortmentProduction::deleteProduction('PROD-004');
        dump($response->getData());

        $this->assertEquals(403, $response->status());
        $this->assertEquals('Cannot delete production that is in progress', $response->getData()->message);
        $this->assertDatabaseHas('assortment_production', [
            'production_number' => 'PROD-004'
        ]);
    }
}
