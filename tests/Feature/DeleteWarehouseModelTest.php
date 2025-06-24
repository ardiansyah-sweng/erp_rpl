<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;

class DeleteWarehouseModelTest extends TestCase
{
    /**
     * Test gagal hapus warehouse karena digunakan di rm_whouse_id
     */
    public function testGagalHapusKarenaDipakaiDiRmWhouse()
    {
        $usedRmId = 17; // berdasarkan data: dipakai di PROD-001 dan PROD-002

        $warehouse = new Warehouse();
        $result = $warehouse->deleteWarehouse($usedRmId);
        $data = $result->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals(
            'Warehouse tidak dapat dihapus karena sedang digunakan di tabel assortment_production.',
            $data['message']
        );
    }

    /**
     * Test gagal hapus warehouse karena digunakan di fg_whouse_id
     */
    public function testGagalHapusKarenaDipakaiDiFgWhouse()
    {
        $usedFgId = 8; // berdasarkan data: dipakai di PROD-001

        $warehouse = new Warehouse();
        $result = $warehouse->deleteWarehouse($usedFgId);
        $data = $result->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals(
            'Warehouse tidak dapat dihapus karena sedang digunakan di tabel assortment_production.',
            $data['message']
        );
    }

    /**
     * Test gagal hapus warehouse karena ID tidak ditemukan
     */
    public function testGagalHapusWarehouseTidakDitemukan()
    {
        $notExistId = 999999; // ID besar yang hampir pasti tidak ada

        $warehouse = new Warehouse();
        $result = $warehouse->deleteWarehouse($notExistId);
        $data = $result->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Warehouse tidak ditemukan.', $data['message']);
    }

    /**
     * Test sukses hapus warehouse yang tidak digunakan
     */
    public function testBerhasilHapusWarehouseYangTidakDipakai()
    {
        // Ambil ID warehouse yang tidak digunakan di rm_whouse_id atau fg_whouse_id
        $usedIds = DB::table('assortment_production')
            ->select('rm_whouse_id')
            ->union(DB::table('assortment_production')->select('fg_whouse_id'))
            ->pluck('rm_whouse_id')
            ->toArray();

        $availableWarehouse = DB::table('warehouse')
            ->whereNotIn('id', $usedIds)
            ->first();

        if (!$availableWarehouse) {
            $this->markTestSkipped('Tidak ada warehouse yang tidak sedang digunakan.');
        }

        $warehouse = new Warehouse();
        $result = $warehouse->deleteWarehouse($availableWarehouse->id);
        $data = $result->getData(true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Warehouse berhasil dihapus.', $data['message']);
        $this->assertDatabaseMissing('warehouse', ['id' => $availableWarehouse->id]);
    }
}
