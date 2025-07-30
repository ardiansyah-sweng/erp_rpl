<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Warehouse;

class GetWareHouseAllTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_warehouses()
    {
        // Arrange: buat beberapa warehouse
        Warehouse::factory()->create(['warehouse_name' => 'Gudang A']);
        Warehouse::factory()->create(['warehouse_name' => 'Gudang B']);

        // Act: panggil fungsi getWareHouseAll
        $warehouses = (new Warehouse())->getWareHouseAll();

        // Assert: pastikan data yang diambil sesuai
        $this->assertCount(2, $warehouses);
        $this->assertEquals('Gudang A', $warehouses[0]->warehouse_name);
        $this->assertEquals('Gudang B', $warehouses[1]->warehouse_name);
    }
}
