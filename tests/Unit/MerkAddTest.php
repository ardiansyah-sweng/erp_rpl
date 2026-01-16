<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerkAddTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_add_merk_successfully()
    {
        // Ambil nama tabel secara dinamis dari model
        $tableName = (new Merk)->getTable();
        
        $namaMerk = "Brand Yusuf 316";
        $merk = Merk::addMerk($namaMerk, 1);

        $this->assertEquals($namaMerk, $merk->merk);
        $this->assertTrue($merk->is_active);
        
        // Gunakan nama tabel dinamis agar tidak error "table not found"
        $this->assertDatabaseHas($tableName, [
            'merk' => $namaMerk
        ]);
    }

    /** @test */
    public function test_add_merk_with_inactive_status()
    {
        $merk = Merk::addMerk("Brand Inaktif", 0);

        $this->assertFalse($merk->is_active);
        // Memastikan status_label bekerja sesuai accessor di model
        $this->assertEquals('Tidak Aktif', $merk->status_label);
    }
}