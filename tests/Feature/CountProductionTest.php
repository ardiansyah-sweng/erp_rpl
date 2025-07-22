<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortmentProduction;

class CountProductionTest extends TestCase
{
    /**
     * Test menghitung jumlah produksi yang sedang berlangsung,
     * sudah selesai, dan total berdasarkan data yang sudah ada di database.
     *
     * @return void
     */
    public function test_it_counts_existing_production_data()
    {
        // Panggil method countProduction dari model
        $counts = AssortmentProduction::countProduction();

        // Cek bahwa hasilnya adalah array dengan 3 key
        $this->assertIsArray($counts);
        $this->assertArrayHasKey('in_production', $counts);
        $this->assertArrayHasKey('done_production', $counts);
        $this->assertArrayHasKey('total_production', $counts);

        // Cek bahwa nilai-nilai tersebut adalah angka
        $this->assertIsInt($counts['in_production']);
        $this->assertIsInt($counts['done_production']);
        $this->assertIsInt($counts['total_production']);

        // Cetak hasil (untuk verifikasi manual jika dibutuhkan)
        fwrite(STDOUT, json_encode($counts, JSON_PRETTY_PRINT) . "\n");
    }
}
