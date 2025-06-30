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
        // Simulasi data yang valid
        $id = DB::table('bill_of_material')->insertGetId([
            'bom_id' => 'BOM001',
            'bom_name' => 'Test BOM',
            'measurement_unit' => 1, // pastikan ini integer sesuai DB
            'total_cost' => 1000,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Jalankan delete
        $response = $this->delete("/bill-of-material/{$id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Bill of Material deleted successfully.']);

        // Pastikan data benar-benar terhapus
        $this->assertDatabaseMissing('bill_of_material', ['id' => $id]);
    }

    /** @test */
    public function it_handles_nonexistent_id()
    {
        // ID 999 kemungkinan besar tidak ada
        $response = $this->delete('/bill-of-material/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Bill of Material not found.']);
    }

    /** @test */
    public function it_handles_invalid_id_format()
    {
        $response = $this->delete('/bill-of-material/abc');

        // Karena kita tidak validasi tipe ID di controller, bisa 404 atau 500 tergantung DB
        $this->assertTrue(
            in_array($response->status(), [404, 500]),
            'Expected status code 404 or 500 for invalid ID'
        );
    }
}
