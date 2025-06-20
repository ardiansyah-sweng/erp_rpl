<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UpdateAssortmentProductionTest extends TestCase
{
    public function test_update_production_success()
    {
        // Ambil data yang sudah ada
        $existing = DB::table('assortment_production')->whereNotNull('id')->first();

        // Skip test jika tidak ada data sama sekali
        if (!$existing) {
            $this->markTestSkipped('Data tidak ditemukan di tabel assortment_production.');
        }

        $updatedProductionNumber = 'PT' . rand(10000, 99999);

        $response = $this->putJson("/assortment_production/update/{$existing->id}", [
            'in_production' => false,
            'production_number' => $updatedProductionNumber,
            'sku' => 'SKU456',
            'branch_id' => 2,
            'rm_whouse_id' => 3,
            'fg_whouse_id' => 4,
            'production_date' => '2025-06-19',
            'finished_date' => '2025-06-20',
            'description' => 'Update data berhasil',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data berhasil diperbarui']);
    }

    public function test_update_production_not_found()
    {
        $response = $this->putJson("/assortment_production/update/99999999", [
            'in_production' => true,
            'production_number' => 'PNOTF999',
            'sku' => 'SKU789',
            'branch_id' => 1,
            'rm_whouse_id' => 1,
            'fg_whouse_id' => 2,
            'production_date' => '2025-06-21',
            'finished_date' => '2025-06-22',
            'description' => 'Tidak ada ID ini',
        ]);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Data dengan ID tersebut tidak ditemukan']);
    }

    public function test_update_production_validation_error()
    {
        $existing = DB::table('assortment_production')->whereNotNull('id')->first();

        if (!$existing) {
            $this->markTestSkipped('Data tidak tersedia untuk validasi.');
        }

        $response = $this->putJson("/assortment_production/update/{$existing->id}", [
            'in_production' => 'invalid', 
            'production_number' => str_repeat('X', 20), 
        ]);

        $response->assertStatus(422);
    }
}
