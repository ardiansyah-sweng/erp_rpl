<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DeleteBillOfMaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_existing_bom()
    {
        // Simulasi data yang valid dengan bom_id unik
        $bomId = 'BOM' . mt_rand(1000, 9999); // ID pendek, aman
        $id = DB::table('bill_of_material')->insertGetId([
            'bom_id' => $bomId,
            'bom_name' => 'Test BOM',
            'measurement_unit' => 1,
            'total_cost' => 1000,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Jalankan permintaan DELETE ke endpoint
        $response = $this->delete("/bill-of-material/{$id}");

        // Pastikan response berhasil
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Bill of Material deleted successfully.']);

        // Pastikan data benar-benar terhapus dari database
        $this->assertDatabaseMissing('bill_of_material', ['id' => $id]);
    }

    /** @test */
    public function it_handles_nonexistent_id()
    {
        // ID 999 kemungkinan besar tidak ada
        $response = $this->delete('/bill-of-material/999');

        // Response harus 404 Not Found
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Bill of Material not found.']);
    }

    /** @test */
    public function it_handles_invalid_id_format()
    {
        // Kirim ID tidak valid (non-numeric)
        $response = $this->delete('/bill-of-material/abc');

        // Bisa 404 jika route tidak match atau 500 jika error di controller
        $this->assertTrue(
            in_array($response->status(), [404, 500]),
            'Expected status code 404 or 500 for invalid ID format'
        );
    }
}
