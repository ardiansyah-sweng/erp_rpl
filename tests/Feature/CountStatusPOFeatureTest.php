<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PurchaseOrder;

class CountStatusPOFeatureTest extends TestCase
{
    // Tidak pakai RefreshDatabase → agar tidak hapus data
    public function test_count_status_po_from_existing_database()
    {
        // Jalankan fungsi
        $result = PurchaseOrder::countStatusPO();

        // Tampilkan hasil di konsol saat test
        foreach ($result as $status => $row) {
            echo "Status: {$status} → Total: {$row->total}\n";
        }

        // Contoh: Assert bahwa status tertentu setidaknya ada
        $this->assertTrue($result->has('Approved'), 'Approved status should be present');
    }
}
