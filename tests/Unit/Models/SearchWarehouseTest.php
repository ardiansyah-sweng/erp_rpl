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
    public function test_modelSearchWarehouse_returns_matching_results(): void
    {
      
        Warehouse::create([
            'warehouse_name'    => 'Gudang Dolores',
            'warehouse_code'    => 'GD001',
            'warehouse_address' => 'Jl. Mawar No. 5',
        ]);

        
        $results = (new Warehouse)->searchWarehouse('dolores');

       
        $this->assertNotEmpty($results, 'Search result should not be empty');
        $this->assertTrue(
            $results->pluck('warehouse_name')->contains('Gudang Dolores'),
            'Search result should include "Gudang Dolores"'
        );
    }

    #[Test]
    public function test_searchWarehouse_returns_empty_for_no_match(): void
    {
        
        Warehouse::create([
            'warehouse_name'    => 'Gudang Melati',
            'warehouse_code'    => 'GD002',
            'warehouse_address' => 'Jl. Kenanga No. 10',
        ]);

       
        $results = (new Warehouse)->searchWarehouse('xyz123');

        
        $this->assertTrue(
            $results->isEmpty(),
            'Search should return empty result when no warehouse matches keyword'
        );
    }

    #[Test]
    public function test_searchWarehouse_can_search_by_name_and_address(): void
    {
       
        Warehouse::create([
            'warehouse_name'    => 'Gudang Besar',
            'warehouse_code'    => 'GB999',
            'warehouse_address' => 'Jl. Anggrek No. 22',
        ]);

       
        $byName = (new Warehouse)->searchWarehouse('besar');
        $this->assertNotEmpty($byName, 'Search by warehouse name should return result');

        
        $byAddress = (new Warehouse)->searchWarehouse('anggrek');
        $this->assertNotEmpty($byAddress, 'Search by warehouse address should return result');
    }

    #[Test]
    public function test_searchWarehouse_is_case_insensitive(): void
    {
       
        Warehouse::create([
            'warehouse_name'    => 'Gudang Sakura',
            'warehouse_code'    => 'GDS001',
            'warehouse_address' => 'Jl. Sakura No. 15',
        ]);

       
        $results = (new Warehouse)->searchWarehouse('SAKURA');

        $this->assertNotEmpty(
            $results,
            'Search should return result even with uppercase keyword'
        );

        $this->assertTrue(
            $results->pluck('warehouse_name')->contains('Gudang Sakura'),
            'Search should find the warehouse regardless of letter case'
        );
    }
}
