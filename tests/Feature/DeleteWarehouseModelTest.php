<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;

class DeleteWarehouseModelTest extends TestCase
{
    /**
     * Test 1: Hapus warehouse yang tidak digunakan di assortment_production
     */
    public function testDeleteWarehouseSuccess()
    {
        // Buat warehouse dummy dengan id 99
        $warehouse = Warehouse::firstOrCreate(
            ['id' => 99],
            [
                'warehouse_name' => 'Warehouse Bebas',
                'warehouse_address' => 'Alamat Bebas',
                'warehouse_telephone' => '081234567890'
            ]
        );

        // Pastikan warehouse tidak digunakan di assortment_production
        DB::table('assortment_production')->where('rm_whouse_id', 99)->delete();
        DB::table('assortment_production')->where('fg_whouse_id', 99)->delete();

        // Hapus warehouse
        $result = $warehouse->deleteWarehouse($warehouse->id);
        $data = $result->getData(true);

        // Pastikan warehouse benar-benar terhapus
        $this->assertDatabaseMissing('warehouse', ['id' => 99]);
        $this->assertTrue($data['success']);
        $this->assertEquals('Warehouse berhasil dihapus.', $data['message']);
    }

    /**
     * Test 2: Gagal hapus warehouse karena digunakan di kolom rm_whouse_id
     */
    public function testDeleteWarehouseFailUsedInRmWhouse()
    {
        $warehouse = Warehouse::firstOrCreate(
            ['id' => 17],
            [
                'warehouse_name' => 'Warehouse RM Dipakai',
                'warehouse_address' => 'Alamat RM',
                'warehouse_telephone' => '081100001111'
            ]
        );

        DB::table('assortment_production')->insertOrIgnore([
            'production_number' => 'PROD-RM-TEST',
            'sku' => 'SKU-RM',
            'branch_id' => 1,
            'rm_whouse_id' => 17,
            'fg_whouse_id' => 100,
            'production_date' => now()
        ]);

        $result = $warehouse->deleteWarehouse($warehouse->id);
        $data = $result->getData(true);

        $this->assertDatabaseHas('warehouse', ['id' => 17]);
        $this->assertFalse($data['success']);
        $this->assertEquals(
            'Warehouse tidak dapat dihapus karena sedang digunakan di tabel assortment_production.',
            $data['message']
        );
    }

    /**
     * Test 3: Gagal hapus warehouse karena digunakan di kolom fg_whouse_id
     */
    public function testDeleteWarehouseFailUsedInFgWhouse()
    {
        $warehouse = Warehouse::firstOrCreate(
            ['id' => 8],
            [
                'warehouse_name' => 'Warehouse FG Dipakai',
                'warehouse_address' => 'Alamat FG',
                'warehouse_telephone' => '082222333444'
            ]
        );

        DB::table('assortment_production')->insertOrIgnore([
            'production_number' => 'PROD-FG-TEST',
            'sku' => 'SKU-FG',
            'branch_id' => 2,
            'rm_whouse_id' => 100,
            'fg_whouse_id' => 8,
            'production_date' => now()
        ]);

        $result = $warehouse->deleteWarehouse($warehouse->id);
        $data = $result->getData(true);

        $this->assertDatabaseHas('warehouse', ['id' => 8]);
        $this->assertFalse($data['success']);
        $this->assertEquals(
            'Warehouse tidak dapat dihapus karena sedang digunakan di tabel assortment_production.',
            $data['message']
        );
    }

    /**
     * Test 4: Gagal hapus karena warehouse tidak ditemukan
     */
    public function testDeleteWarehouseNotFound()
    {
        $notExistId = 999999;

        $warehouse = new Warehouse();
        $result = $warehouse->deleteWarehouse($notExistId);
        $data = $result->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Warehouse tidak ditemukan.', $data['message']);
    }
}
