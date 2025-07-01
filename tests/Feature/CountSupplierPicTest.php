<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierPic;

class CountSupplierPicTest extends TestCase
{
    public function test_model_countPICByStatus(): void
    {
        //. Ambil salah satu PIC secara acak dari database
        $pic = SupplierPic::inRandomOrder()->first();
        $this->assertNotNull($pic, 'Tidak ada data PIC di tabel supplier_pics');

        $supplier_id = $pic->supplier_id;

        // Panggil fungsi untuk hitung berdasarkan status
        $counts = SupplierPic::countPICByStatus($supplier_id);
        $this->assertIsArray($counts, 'Fungsi tidak mengembalikan array');

        echo "\nSupplier ID: $supplier_id\n";
        echo "Active: {$counts['active']}\n";
        echo "Inactive: {$counts['inactive']}\n";
        echo "Total: {$counts['total']}\n";

        $this->assertGreaterThanOrEqual(0, $counts['active']);
        $this->assertGreaterThanOrEqual(0, $counts['inactive']);
        $this->assertEquals($counts['active'] + $counts['inactive'], $counts['total']);
    }
}
