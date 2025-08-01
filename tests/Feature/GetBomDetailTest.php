<?php

namespace Tests\Feature;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;

class GetBomDetailTest extends TestCase
{
    /** @test */
    public function it_can_get_bom_detail_by_id()
    {
        // Ambil ID pertama dari tabel bill_of_material (pastikan datanya memang sudah ada)
        $bom = DB::table('bill_of_material')->first();

        // Jika tidak ada data, hentikan test dengan pesan
        if (!$bom) {
            $this->markTestIncomplete('Tidak ada data di tabel bill_of_material. Tambahkan data terlebih dahulu.');
        }

        // Lakukan GET request
        $response = $this->get('/bom/detail/' . $bom->id);


        // Tes status HTTP
        $response->assertStatus(200);

        // Tes struktur JSON
        $response->assertJsonStructure([
            'id',
            'bom_id',
            'bom_name',
            'measurement_unit',
            'total_cost',
            'active',
            'created_at',
            'updated_at',
            'details' => [
                '*' => [
                    'id',
                    'bom_id',
                    'sku',
                    'quantity',
                    'cost',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }
}
