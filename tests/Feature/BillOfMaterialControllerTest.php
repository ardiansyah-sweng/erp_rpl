<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class BillOfMaterialControllerTest extends TestCase
{
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
/** @test */
public function it_returns_bom_by_id_if_exists()
{
    // Hapus bom_id yang sama jika sudah ada (hindari duplicate)
    DB::table('bill_of_material')->where('bom_id', 'BOM-001')->delete();

    // Insert dummy
    $id = DB::table('bill_of_material')->insertGetId([
        'bom_id'           => 'BOM-001',
        'bom_name'         => 'BOM-BOM-001',
        'measurement_unit' => 31,
        'total_cost'       => 38489,
        'active'           => 1,
        'created_at'       => now(),
        'updated_at'       => now(),
    ]);

    // Akses endpoint
    $response = $this->get("/bom/detail/{$id}");

    // Validasi response
    $response->assertStatus(200)
             ->assertJson([
                 'id'               => $id,
                 'bom_id'           => 'BOM-001',
                 'bom_name'         => 'BOM-BOM-001',
                 'measurement_unit' => 31,
                 'total_cost'       => 38489,
                 'active'           => 1,
             ]);

    // Bersihkan setelah test (opsional)
    DB::table('bill_of_material')->where('id', $id)->delete();
}

}