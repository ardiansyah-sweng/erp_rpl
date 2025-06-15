<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DeleteWarehouseTest extends TestCase
{
    public function test_delete_warehouse_not_used_in_assortment_production()
    {
        // Ambil warehouse yang tidak dipakai di assortment_production
        $warehouse = Warehouse::whereNotIn('id', function ($query) {
            $query->select('rm_whouse_id')->from('assortment_production')
                ->union(
                    DB::table('assortment_production')->select('fg_whouse_id')
                );
        })->first();

        if (!$warehouse) {
            $this->markTestSkipped('Semua warehouse sedang digunakan. Tidak ada yang bisa diuji untuk dihapus.');
        }

        $response = $this->delete(route('warehouse.delete', ['id' => $warehouse->id]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('warehouse', [
            'id' => $warehouse->id,
        ]);
    }

    public function test_delete_warehouse_used_in_assortment_production_should_fail()
    {
        // Ambil warehouse yang sedang digunakan di assortment_production
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
            $this->markTestSkipped('Warehouse yang dipakai di assortment_production tidak ditemukan di tabel warehouse.');
        }

        $response = $this->delete(route('warehouse.delete', ['id' => $warehouse->id]));

        $response->assertRedirect();
        $this->assertDatabaseHas('warehouse', [
            'id' => $warehouse->id,
        ]);
    }
}
