<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeleteBillOfMaterialTest extends TestCase
{
    /** @test */
    public function it_deletes_existing_bom()
    {
        // Insert data langsung ke DB
        $id = DB::table('bill_of_material')->insertGetId([
            'bom_id' => 'BOM' . mt_rand(1000, 9999),
            'bom_name' => 'Test BOM',
            'measurement_unit' => 1,
            'total_cost' => 1000,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Jalankan request DELETE
        $response = $this->delete("/bill-of-material/{$id}");

        // Cek response sukses
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Bill of Material deleted successfully.']);

        // Pastikan datanya terhapus
        $this->assertDatabaseMissing('bill_of_material', ['id' => $id]);
    }

    /** @test */
    public function it_handles_nonexistent_id()
    {
        // Delete ID yang tidak ada
        $response = $this->delete('/bill-of-material/9999');

        // Cek responsenya 404
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Bill of Material not found.']);
    }

    /** @test */
    public function it_handles_invalid_id_format()
    {
        $response = $this->delete('/bill-of-material/invalid');

        $this->assertTrue(
            in_array($response->status(), [404, 500]),
            'Expected status code 404 or 500 for invalid ID format'
        );
    }
}
