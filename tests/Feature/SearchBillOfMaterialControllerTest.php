<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchBillOfMaterialControllerTest extends TestCase
{
    public function test_search_bill_of_material_endpoint_returns_expected_data()
    {
        // Gunakan keyword yang sudah ada di database
        $keyword = 'debitis';

        // Kirim request ke endpoint pencarian
        $response = $this->get("/bill-of-material/search/{$keyword}");

        // Pastikan status 200 dan pesan sukses
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Data Bill of Material berhasil ditemukan.',
                 ]);

        // Ambil bagian data hasil paginasi
        $json = $response->json();

        // Validasi struktur paginasi
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('data', $json['data']);
        $this->assertIsArray($json['data']['data']);
        $this->assertNotEmpty($json['data']['data'], 'Tidak ada data ditemukan di hasil pencarian.');

        // Ambil baris pertama dari hasil data
        $firstItem = $json['data']['data'][0];

        // Cek bahwa bom_name mengandung keyword yang dicari
        $this->assertStringContainsStringIgnoringCase(
            $keyword,
            $firstItem['bom_name'],
            "bom_name tidak mengandung keyword '{$keyword}'"
        );

        // Cek ulang dengan assertTrue jika perlu
        $found = collect($json['data']['data'])->contains(function ($item) use ($keyword) {
            return stripos($item['bom_name'], $keyword) !== false;
        });

        $this->assertTrue($found, "Tidak ditemukan bom_name yang mengandung keyword '{$keyword}'");

        // Tampilkan pesan jika berhasil
        echo "✅ Test pencarian BOM dengan keyword '{$keyword}' berhasil.\n";

        // Pastikan struktur JSON hasil sesuai
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'bom_id',
                        'bom_name',
                        'measurement_unit',
                        'total_cost',
                        'active',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ]);
    }
}