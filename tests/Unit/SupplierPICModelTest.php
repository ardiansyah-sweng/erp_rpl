<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SupplierPICModel; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class SupplierPICModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_count_supplier_pic_berhasil()
    {
        // 1. Matikan aturan database sementara
        Schema::disableForeignKeyConstraints();

        // 2. Buat Data Dummy
        SupplierPICModel::forceCreate([
            'supplier_id'   => 'S1', 
            'name'          => 'Budi',
            'phone_number'  => '08111',
            'email'         => 'budi@test.com',
            'assigned_date' => '2026-01-01' // 
        ]);
        
        SupplierPICModel::forceCreate([
            'supplier_id'   => 'S1', 
            'name'          => 'Ani',
            'phone_number'  => '08222',
            'email'         => 'ani@test.com',
            'assigned_date' => '2026-01-01'
        ]);
        
        SupplierPICModel::forceCreate([
            'supplier_id'   => 'S1', 
            'name'          => 'Siti',
            'phone_number'  => '08333',
            'email'         => 'siti@test.com',
            'assigned_date' => '2026-01-01'
        ]);
        
        // Data pengecoh (ID beda: S2)
        SupplierPICModel::forceCreate([
            'supplier_id'   => 'S2', 
            'name'          => 'Joko',
            'phone_number'  => '08444',
            'email'         => 'joko@test.com',
            'assigned_date' => '2026-01-01'
        ]);

        // 3. Panggil Fungsi (Cari S1)
        $hasil = SupplierPICModel::countSupplierPIC('S1');

        // 4. Cek Hasil (Harusnya ketemu 3 orang: Budi, Ani, Siti)
        $this->assertEquals(3, $hasil, 'Jumlah PIC salah hitung!');
    }
}