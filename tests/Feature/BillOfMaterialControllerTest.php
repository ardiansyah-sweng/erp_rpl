<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BillOfMaterialControllerTest extends TestCase
{

    /**
     * Test ambil data BOM yang valid.
     */
    public function test_get_bom_by_valid_id()
    {
        $response = $this->get('/bom/detail/1');

        $response->assertStatus(200)
                 ->assertJson([
                'id' => 1,
                'bom_id' => 'BOM-001',
                'bom_name' => 'BOM-BOM-001',
                'measurement_unit' => 31,
                'total_cost' => 38489,
                'active' => 1,
                'created_at' => '2025-06-17 09:24:24',
                'updated_at' => '2025-06-17 09:24:24'
                 ]);
    }

    /**
     * Test ambil data BOM dengan ID yang tidak ada.
     */
    public function test_get_bom_by_invalid_id()
    {
        $response = $this->get('/bom/detail/999');

        $response->assertStatus(404);
    }
}

    // Optional: agar tidak perlu login/middleware saat testing
    use WithoutMiddleware;

    /** @test */
    public function it_deletes_bill_of_material_by_id()
    {
        // 1. Hapus data dengan bom_id 'BOM-999' jika ada (hindari duplikat)
        DB::table('bill_of_material')->where('bom_id', 'BOM-999')->delete();

        // 2. Insert data dummy
        $id = DB::table('bill_of_material')->insertGetId([
            'bom_id'            => 'BOM-999',
            'bom_name'          => 'BOM-BOM-999',
            'measurement_unit'  => 31,
            'total_cost'        => 9999,
            'active'            => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 3. Panggil endpoint DELETE
        $response = $this->delete('/bill-of-material/' . $id);

        // 4. Cek response & data terhapus
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Bill of Material deleted successfully.']);
        $this->assertDatabaseMissing('bill_of_material', ['id' => $id]);
    }

    /** @test */
    public function it_returns_404_if_bill_of_material_not_found()
    {
        // Pastikan ID tidak ada di database
        $id = 999999;

        $response = $this->delete('/bill-of-material/' . $id);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Bill of Material not found.']);
    }
}

