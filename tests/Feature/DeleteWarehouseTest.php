<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteWarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_warehouse()
    {
        // Arrange
        $warehouse = Warehouse::create([
            'warehouse_name' => 'Gudang Coba',
            'warehouse_address' => 'Jl. Uji Test 123',
            'warehouse_telephone' => '081234567890',
            'is_active' => 1,
        ]);

        // Act
        $response = $this->delete(route('warehouse.delete', ['id' => $warehouse->id]));

        // Assert
        $response->assertRedirect(); // karena kamu redirect back
        $this->assertDatabaseMissing('warehouse', [
            'id' => $warehouse->id,
        ]);
    }
}
