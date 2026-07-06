<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Helpers\EncryptionHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DuplicatePurchaseOrderTest extends TestCase
{
    // Menggunakan RefreshDatabase agar database kembali bersih setelah test
    use RefreshDatabase;

    public function test_duplicate_purchase_order_berhasil()
    {
        // 1. Siapkan data dummy PO di database
        $poNumberOriginal = 'PO1234';
        DB::table('purchase_order')->insert([
            'po_number'   => $poNumberOriginal,
            'supplier_id' => 'SUP999',
            'branch_id'   => 1,
            'total'       => 150000,
            'status'      => 'Draft',
            'order_date'  => now(),
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        // 2. Encrypt PO number (sesuai dengan cara kerja Controller Anda)
        $encryptedId = EncryptionHelper::encrypt($poNumberOriginal);

        // 3. Panggil route duplicate
        $response = $this->get(route('purchase_orders.duplicate', $encryptedId));

        // 4. Pastikan redirect sukses
        $response->assertStatus(302); // 302 artinya Redirect
        $response->assertSessionHas('success');

        // 5. Cek apakah ada data PO baru di database (selain PO asli)
        // Kita hitung, seharusnya total ada 2 PO (1 asli + 1 duplikat)
        $poCount = DB::table('purchase_order')->count();
        $this->assertEquals(2, $poCount);

        // 6. Pastikan nomor PO baru bukan PO1234 (tanda bahwa duplikasi berhasil)
        $newPo = DB::table('purchase_order')->where('po_number', '!=', $poNumberOriginal)->first();
        $this->assertNotNull($newPo);
        $this->assertStringStartsWith('PO', $newPo->po_number);
    }
}