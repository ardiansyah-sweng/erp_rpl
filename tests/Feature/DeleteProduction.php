<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortmentProduction;

class DeleteProduction extends TestCase
{
    public function test_delete_existing_production()
    {

        $this->assertDatabaseHas('assortment_production', [
            'production_number' => 'PROD-002'
        ]);
        $response = AssortmentProduction::deleteProduction('PROD-002');

        $this->assertEquals('Production deleted successfully', $response->getData()->message);
        $this->assertDatabaseMissing('assortment_production', [
            'production_number' => 'PROD-002'
        ]);
    }
}
