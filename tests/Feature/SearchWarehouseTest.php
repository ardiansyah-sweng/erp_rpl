<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class SearchWarehouseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_modelSearchWarehouse(): void
    {
        Warehouse::create([
            'warehouse_name' => 'Gudang dolor',
            'warehouse_address' => 'Dk. Pattimura No. 129, Lhokseumawe',
            'warehouse_telephone' => '021 7327 267',
            'is_active' => 1,
        ]);

        Warehouse::create([
            'warehouse_name' => 'Gudang officia',
            'warehouse_address' => 'Tebing Tinggi',
            'warehouse_telephone' => '915 5296 8268',
            'is_active' => 0,
        ]);

        $results = (new Warehouse)->searchWarehouse('dolor');

        $this->assertCount(1, $results);
        $this->assertEquals('Gudang dolor', $results->first()->warehouse_name);
    }
}
