<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class WarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['db_tables.warehouse' => 'warehouses']);
    }

    // --- TEST DELETE (DARI BRANCH FEAT) ---

    #[Test]
    public function it_can_delete_a_warehouse_successfully()
    {
        $user = User::factory()->create(); 
        $warehouse = Warehouse::factory()->create(); 

        $response = $this->actingAs($user) 
                         ->delete(route('warehouses.destroy', $warehouse->id));

        $response->assertStatus(200); 
        $this->assertDatabaseMissing('warehouses', ['id' => $warehouse->id]);
    }

    #[Test]
    public function it_returns_404_when_deleting_a_non_existent_warehouse()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
                         ->delete(route('warehouses.destroy', 999));

        $response->assertStatus(404);
    }

    // --- TEST COUNT (DARI BRANCH DEVELOPMENT) ---

    #[Test]
    public function test_count_warehouse_returns_correct_total()
    {
        Warehouse::factory()->count(5)->create();
        $response = $this->get('/warehouse/count');

        $response->assertStatus(200)
                 ->assertJson(['total_warehouse' => 5]);
    }

    #[Test]
    public function test_count_warehouse_returns_zero_when_empty()
    {
        $response = $this->get('/warehouse/count');
        $response->assertStatus(200)
                 ->assertJson(['total_warehouse' => 0]);
    }
}