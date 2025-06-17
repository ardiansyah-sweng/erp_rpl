<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateAssortmentProductionTest extends TestCase
{
    public function test_update_production_success()
    {
        $uniqueNumber = 'PTEST' . Str::random(3); 
        $uniqueNumberUpdated = 'PTEST' . Str::random(4); 

        $id = DB::table('assortment_production')->insertGetId([
            'in_production' => true,
            'production_number' => $uniqueNumber,
            'sku' => 'SKU123',
            'branch_id' => 1,
            'rm_whouse_id' => 1,
            'fg_whouse_id' => 2,
            'production_date' => '2025-06-17',
            'finished_date' => '2025-06-18',
            'description' => 'Deskripsi awal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->putJson("/assortment_production/update/{$id}", [
            'in_production' => false,
            'production_number' => $uniqueNumberUpdated,
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
            'production_number' => 'PNOTF' . Str::random(4),
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
        $uniqueNumber = 'VAL' . Str::random(5);

        $id = DB::table('assortment_production')->insertGetId([
            'in_production' => true,
            'production_number' => $uniqueNumber,
            'sku' => 'SKUVLD',
            'branch_id' => 1,
            'rm_whouse_id' => 1,
            'fg_whouse_id' => 1,
            'production_date' => '2025-06-17',
            'finished_date' => '2025-06-18',
            'description' => 'Validasi test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->putJson("/assortment_production/update/{$id}", [
            'in_production' => 'invalid', // harus boolean
            'production_number' => str_repeat('X', 20), 
        ]);

        $response->assertStatus(422); // Validasi error
    }
}
