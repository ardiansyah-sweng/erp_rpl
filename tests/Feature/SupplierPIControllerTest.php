<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierPIControllerTest extends TestCase
{
    /** @test */
    public function it_returns_existing_supplier_pic_with_lama_assigned()
    {
        $supplierPic = DB::table('supplier_pic')->first();

        $this->assertNotNull($supplierPic, 'Tidak ada data di tabel supplier_pic.');

        dump('Data dari DB:', $supplierPic);

        $assignedDate = Carbon::parse($supplierPic->assigned_date)->startOfDay();
        $expectedLama = $assignedDate->diffInDays(Carbon::now()->startOfDay());

        $response = $this->get('/supplierPic/' . $supplierPic->supplier_id);

        dump('Response dari endpoint:', $response->json());

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'supplier_id' => $supplierPic->supplier_id,
            ],
            'lama_assigned' => $expectedLama,
        ]);
    }

    /** @test */
    public function it_can_update_supplier_pic_via_form()
    {
        // 1. Arrange: Buat data dummy
        $pic = SupplierPic::factory()->create();
        $newData = [
            'supplier_id' => $pic->supplier_id,
            'name' => 'John Doe Updated',
            'phone_number' => '08123456789',
            'email' => 'john.updated@example.com',
            'assigned_date' => '2026-04-25',
            'active' => true,
        ];

        // 2. Act: Kirim request ke method update()
        $response = $this->put("/supplier/pic/update/{$pic->id}", $newData);

        // 3. Assert: Cek redirect dan database
        $response->assertStatus(302);
        $this->assertDatabaseHas('supplier_pic', [
            'id' => $pic->id,
            'name' => 'John Doe Updated',
            'email' => 'john.updated@example.com'
        ]);
    }

    /** @test */
    public function it_can_update_supplier_pic_detail_via_json()
    {
        // 1. Arrange: Buat data dummy
        $pic = SupplierPic::factory()->create();
        $jsonPayload = [
            'supplier_id' => $pic->supplier_id,
            'name' => 'JSON Update Name',
            'phone_number' => '0899999999',
            'email' => 'json@example.com',
            'assigned_date' => '2026-05-01',
        ];

        // 2. Act: Kirim request ke method updateSupplierPICDetail()
        $response = $this->json('POST', "/supplier/pic/update-detail/{$pic->id}", $jsonPayload);

        // 3. Assert: Cek respons JSON
        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
        
        $this->assertDatabaseHas('supplier_pic', [
            'id' => $pic->id,
            'name' => 'JSON Update Name'
        ]);
    }

    /** @test */
    public function it_returns_404_for_invalid_supplier_id()
    {
        $response = $this->get('/supplierPic/NON_EXISTENT_ID');

        dump('Response untuk ID tidak ditemukan:', $response->json());

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Data not found',
        ]);
    }
}
