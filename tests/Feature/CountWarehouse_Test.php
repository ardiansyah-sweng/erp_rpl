<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountWarehouse_Test extends TestCase
{
    use RefreshDatabase;

    public function test_count_warehouse()
    {
        Warehouse::factory()->count(65)->create();

        $count = Warehouse::count();
        dump("Jumlah warehouse:", $count);
        
        $this->assertEquals(65, $count);
    }
}
