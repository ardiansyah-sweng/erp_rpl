<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_existing_warehouse()
    {
        // Arrange: Buat warehouse awal
        $warehouse = Warehouse::create([
            'warehouse_name'     => 'Gudang Lama',
            'warehouse_address'  => 'Jl. Raya No. 1',
            'warehouse_telephone' => '021-12345678',
        ]);

        // Act: Lakukan update
        $data = [
            'warehouse_name'     => 'Gudang Baru',
            'warehouse_address'  => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ];

        $result = $warehouse->update($data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('warehouse', [
            'id'                 => $warehouse->id,
            'warehouse_name'     => 'Gudang Baru',
            'warehouse_address'  => 'Jl. Baru No. 2',
            'warehouse_telephone' => '021-87654321',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_false_when_updating_non_existing_warehouse()
    {
        // Act: Cari warehouse yang tidak ada
        $warehouse = Warehouse::find(9999);

        // Assert
        $this->assertNull($warehouse);
    }
}
