<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillOfMaterialModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteBillOfMaterialTest extends TestCase
{
    use RefreshDatabase; // ini akan reset database sebelum tiap test

    /** @test */
   public function it_can_delete_existing_bill_of_material()
{
    // Buat data dummy dan simpan ke variabel
    $bom = BillOfMaterialModel::create([
        'bom_id' => 'BOM0001',
        'bom_name' => 'Test BOM',
        'measurement_unit' => 1, // HARUS angka, bukan 'kg'
        'total_cost' => 1000,
        'active' => true,
    ]);

    // Lakukan request delete
    $response = $this->delete("/bill-of-material/{$bom->id}");

    // Assert response OK
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Bill of Material berhasil dihapus.']);

    // Pastikan data terhapus
    $this->assertDatabaseMissing('bill_of_material', ['id' => $bom->id]);
}


    /** @test */
    public function it_returns_404_if_bom_not_found()
    {
        $response = $this->delete('/bill-of-material/9999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Data tidak ditemukan.']);
    }
}
