<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerkModelTest extends TestCase
{
    use RefreshDatabase;

    // TUGAS 214
    public function test_update_merk_berhasil()
    {
        // 1. Buat data dummy menggunakan fungsi addMerk yang sudah ada
        $merkAwal = Merk::addMerk('Merk Lama Banget');

        // 2. Siapkan data baru untuk update
        $dataUpdate = [
            'merk' => 'Merk Update Berhasil'
        ];

        // 3. Panggil fungsi updateMerk (Panggil secara Static :: karena kodemu static)
        $hasil = Merk::updateMerk($merkAwal->id, $dataUpdate);

        // 4. Cek apakah berhasil berubah di database
        $this->assertDatabaseHas($merkAwal->getTable(), [
            'id' => $merkAwal->id,
            'merk' => 'Merk Update Berhasil'
        ]);
        
        // Cek juga variabel hasil kembalian tidak null
        $this->assertNotNull($hasil);
        $this->assertEquals('Merk Update Berhasil', $hasil->merk);
    }
}