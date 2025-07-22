<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Http\Controllers\WarehouseController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class SearchWarehouseTest extends TestCase
{
    #[Test]
    public function test_modelSearchWarehouse_returns_matching_results(): void
    {
        // Pastikan di database sudah ada warehouse_name: 'Gudang dolores'
        $results = (new Warehouse)->searchWarehouse('dolores');

        // Periksa hasil tidak kosong
        $this->assertNotEmpty($results, 'Search result should not be empty');

        // Periksa hasil mengandung data yang dicari
        $this->assertTrue(
            $results->pluck('warehouse_name')->contains('Gudang dolores'),
            'Search result should contain warehouse with name "Gudang dolores"'
        );
    }

    #[Test]
    public function test_controllerSearchWarehouse_returns_matching_results(): void
    {
        $response = $this->get('/warehouse/search?keyword=maxime');

        // Periksa responsenya adalah 200 OK
        $response->assertStatus(200);

        // Periksa responsenya mengandung data yang dicari
        $response->assertSeeText('Gudang maxime');
    }
}
