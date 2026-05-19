<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Warehouse;

class WarehouseModelTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_all_warehouses()
    {
        $warehouses = Warehouse::getWarehouseAll();

        $this->assertNotNull($warehouses);
        $this->assertIsIterable($warehouses);
    }
}
