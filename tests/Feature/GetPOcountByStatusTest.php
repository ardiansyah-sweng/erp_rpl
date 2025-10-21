<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PurchaseOrder;

class GetPOcountByStatusTest extends TestCase
{
    /**
     * Test untuk menghitung jumlah PO berdasarkan status
     */
    public function test_count_po_by_status()
    {
        $status = 'Draft';
        $count = PurchaseOrder::GetPOcountByStatus($status);
        
        // Assert bahwa hasilnya adalah integer
        $this->assertIsInt($count);
        
        // Assert bahwa hasilnya tidak negatif
        $this->assertGreaterThanOrEqual(0, $count);
        
        echo "Jumlah PO dengan status {$status}: {$count}\n";
    }
} 