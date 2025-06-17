<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Warehouse;
use PHPUnit\Framework\Attributes\Test;

class DeleteWarehouseModelTest extends TestCase
{
    #[Test]
    public function test_deleteWarehouse_deletes_existing_record(): void
    {
        // Buat data warehouse secara manual sesuai struktur tabel
        $warehouse = new Warehouse();
        $warehouse->warehouse_name = 'Gudang Test Hapus';
        $warehouse->warehouse_address = 'Jl. Testing No. 1';
        $warehouse->warehouse_telephone = '0800 1234 5678';
        $warehouse->is_rm_whouse = 1;
        $warehouse->is_fg_whouse = 0;
        $warehouse->is_active = 1;
        $warehouse->save();

        // Panggil fungsi deleteWarehouse()
        $result = $warehouse->deleteWarehouse($warehouse->id);

        // Validasi bahwa warehouse berhasil dihapus
        $this->assertTrue($result, 'deleteWarehouse harus mengembalikan true');
        $this->assertNull(Warehouse::find($warehouse->id), 'Warehouse seharusnya sudah tidak ada di database');
    }

    #[Test]
    public function test_deleteWarehouse_returns_false_when_id_not_found(): void
    {
        // Buat instance model untuk memanggil fungsi
        $warehouse = new Warehouse();

        // Panggil fungsi dengan ID yang tidak ada
        $result = $warehouse->deleteWarehouse(99999);

        // Validasi bahwa hasilnya false
        $this->assertFalse($result, 'deleteWarehouse harus mengembalikan false jika ID tidak ditemukan');
    }
}
