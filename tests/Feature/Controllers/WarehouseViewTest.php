<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test untuk memastikan halaman 'Add Warehouse' bisa terbuka.
     */
    public function test_warehouse_view(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/warehouses/create');
        $response->assertStatus(200);
    }
}