<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierPicControllerTest extends TestCase
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
