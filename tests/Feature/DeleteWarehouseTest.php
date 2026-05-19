<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DeleteWarehouseTest extends TestCase
{
    public function test_delete_warehouse_not_used_in_assortment_production()
    {
        // Cari warehouse yang tidak digunakan di assortment_production
        $warehouse = Warehouse::whereNotIn('id', function ($query) {
            $query->select('rm_whouse_id')->from('assortment_production')
                ->union(
                    DB::table('assortment_production')->select('fg_whouse_id')
                );
        })->first();

        if (!$warehouse) {
            $this->markTestSkipped('Semua warehouse sedang digunakan. Tidak ada yang bisa diuji untuk dihapus.');
        }

        $response = $this->deleteJson(route('warehouse.delete', ['id' => $warehouse->id]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Warehouse berhasil dihapus.',
        ]);

        $this->assertDatabaseMissing('warehouse', [
            'id' => $warehouse->id,
        ]);
    }

    public function test_delete_warehouse_used_in_assortment_production_should_fail()
    {
        // Cari warehouse yang digunakan di assortment_production
        $warehouseId = DB::table('assortment_production')
            ->select('rm_whouse_id as id')
            ->union(
                DB::table('assortment_production')->select('fg_whouse_id as id')
            )
            ->pluck('id')
            ->unique()
            ->first();

        if (!$warehouseId) {
            $this->markTestSkipped('Tidak ada warehouse yang digunakan di assortment_production.');
        }

        $warehouse = Warehouse::find($warehouseId);

        if (!$warehouse) {
            $this->markTestSkipped('Warehouse yang dipakai tidak ditemukan di tabel warehouse.');
        }

        $response = $this->deleteJson(route('warehouse.delete', ['id' => $warehouse->id]));

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Warehouse tidak dapat dihapus karena sedang digunakan di tabel assortment_production.',
        ]);

        $this->assertDatabaseHas('warehouse', [
            'id' => $warehouse->id,
        ]);
    }
}
