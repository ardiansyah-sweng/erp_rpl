<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DeleteWarehouseTest extends TestCase
{
    public function test_delete_warehouse()
    {
       $warehouse = Warehouse::whereNotIn('id', function ($query) {
            $query->select('rm_whouse_id')->from('assortment_production')
                ->union(
                    DB::table('assortment_production')->select('fg_whouse_id')
                );
        })->first();

        // Kalau tidak ada data yang bisa dihapus, skip test
        if (!$warehouse) {
            $this->markTestSkipped('Semua warehouse sedang digunakan. Tidak ada yang bisa diuji untuk dihapus.');
        }


        // Act
        $response = $this->delete(route('warehouse.delete', ['id' => $warehouse->id]));

        // Assert
        $response->assertRedirect(); // karena kamu redirect back
        $this->assertDatabaseMissing('warehouse', [
            'id' => $warehouse->id,
        ]);
    }
}