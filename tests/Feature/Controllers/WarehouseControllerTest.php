<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['db_tables.warehouse' => 'warehouses']);
    }

    /**
     * Test count_warehouse function returns correct total
     *
     * @test
     */
    public function test_count_warehouse_returns_correct_total()
    {
        // Arrange: Buat 5 warehouse
        Warehouse::factory()->count(5)->create();

        // Act: Hit endpoint
        $response = $this->get('/warehouse/count');

        // Assert: Pastikan totalnya 5
        $response->assertStatus(200)
                 ->assertJson(['total_warehouse' => 5]);
    }

    /**
     * Test count_warehouse function returns zero when empty
     *
     * @test
     */
    public function test_count_warehouse_returns_zero_when_empty()
    {
        // Act: Hit endpoint tanpa data (database bersih karena RefreshDatabase)
        $response = $this->get('/warehouse/count');

        // Assert: Pastikan totalnya 0
        $response->assertStatus(200)
                 ->assertJson(['total_warehouse' => 0]);
    }

    /**
     * Test count_warehouse function includes all statuses
     *
     * @test
     */
    public function test_count_warehouse_ignores_status_filters()
    {
        // Arrange: Buat 3 warehouse aktif dan 2 tidak aktif
        Warehouse::factory()->active()->count(3)->create();
        Warehouse::factory()->inactive()->count(2)->create();

        // Act
        $response = $this->get('/warehouse/count');

        // Assert: Total harus 5 (mengabaikan status aktif/tidak)
        $response->assertStatus(200)
                 ->assertJson(['total_warehouse' => 5]);
    }
}