<?php

namespace Tests\Feature;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RaffiDeleteProductionTest extends TestCase
{

    public function testDeleteProductionSuccessfully()
    {
        $id = DB::table('assortment_production')->insertGetId([
            'in_production' => false,
            'production_number' => 'PROD-999',
            'sku' => 'TASS-magnam',
            'branch_id' => 6,
            'rm_whouse_id' => 22,
            'fg_whouse_id' => 20,
            'production_date' => '2025-06-02 23:09:55' ,
            'finished_date' => null,
            'description' => 'Produksi Detail untuk BOM BOM-011',
        ]);

        $response = $this->deleteJson("/assort-production/{$id}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data berhasil dihapus']);
        $this->assertDatabaseMissing('assortment_production', ['id' => $id]);
    }

    public function testDeleteProductionNotFound()
    {
        $response = $this->deleteJson('/assort-production/999999');
        $response->assertStatus(404)
                 ->assertJson(['message' => 'Data dengan ID tersebut tidak ditemukan']);
    }

}
