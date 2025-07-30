<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierPIControllerTest extends TestCase
{
    /**
     * Test generate PDF by supplier ID (real data in DB)
     */
    public function testGeneratePdfBySupplierId()
    {
        $supplierId = 'SUP001';

        $response = $this->get('/supplier-pic/pdf/' . $supplierId);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
