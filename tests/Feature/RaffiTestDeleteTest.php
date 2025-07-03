<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class RaffiTestDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteProductionBerhasil()
    {
        // Insert dummy data ke tabel assortment_production
        $id = DB::table('assortment_production')->insertGetId([
            'in_production' => true,
            'production_number' => 'PRD000001',
            'sku' => 'SKU001',
            'branch_id' => 1,
            'rm_whouse_id' => 1,
            'fg_whouse_id' => 1,
            'production_date' => '2025-07-03',
            'finished_date' => null,
            'description' => 'Test delete',
        ]);

        // Panggil endpoint delete
        $response = $this->delete("/production/{$id}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Data berhasil dihapus'
        ]);

        // Pastikan data sudah terhapus
        $this->assertDatabaseMissing('assortment_production', [
            'id' => $id
        ]);
    }

    public function testDeleteProductionNotFound()
    {
        // Panggil endpoint delete dengan id yang tidak ada
        $response = $this->delete('/production/999999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Data dengan ID tersebut tidak ditemukan'
        ]);
    }
}
