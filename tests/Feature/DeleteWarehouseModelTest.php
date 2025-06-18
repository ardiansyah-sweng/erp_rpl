<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DeleteWarehouseModelTest extends TestCase
{
    public function test_delete_existing_warehouse()
    {
        // Ambil warehouse pertama dari database
        $model = new Warehouse();
        $warehouse = $model->first();

        // Jika tidak ada data, skip test
        if (!$warehouse) {
            $this->markTestSkipped('Tidak ada data warehouse di database.');
        }

        // Jalankan fungsi deleteWarehouse
        $result = $model->deleteWarehouse($warehouse->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing($model->getTable(), ['id' => $warehouse->id]);
    }

    public function test_delete_non_existing_warehouse()
    {
        $model = new Warehouse();

        // Pastikan ID ini tidak ada di database
        $nonExistingId = 999999;

        // Jalankan fungsi deleteWarehouse
        $result = $model->deleteWarehouse($nonExistingId);

        // Assert
        $this->assertFalse($result);
    }
}
