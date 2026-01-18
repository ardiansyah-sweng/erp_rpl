<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

class BillOfMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        BillOfMaterial::create([
            'bom_id' => 'BOM001',
            'bom_name' => 'Resep A',
            'measurement_unit' => 'pcs',
            'total_cost' => 1000,
            'active' => true,
        ]);

        BillOfMaterial::create([
            'bom_id' => 'BOM002',
            'bom_name' => 'Resep B',
            'measurement_unit' => 'kg',
            'total_cost' => 2000,
            'active' => false,
        ]);
    }

    public function test_it_returns_paginated_results()
    {
        $results = BillOfMaterial::SearchOfBillMaterial();

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertEquals(2, $results->total());
    }

    public function test_search_by_name_returns_expected()
    {
        $results = BillOfMaterial::SearchOfBillMaterial('Resep A');

        $this->assertEquals(1, $results->total());
        $this->assertEquals('Resep A', $results->first()->bom_name);
    }

    public function test_search_by_partial_keyword()
    {
        $results = BillOfMaterial::SearchOfBillMaterial('BOM002');

        $this->assertEquals(1, $results->total());
        $this->assertEquals('Resep B', $results->first()->bom_name);
    }
}
