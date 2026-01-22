<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\Branch;
use Carbon\Carbon;

class PurchaseOrderbySupplierIDTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_purchase_orders_by_supplier_id(): void
    {
        $supplierId = 'SUP001';

        Supplier::create([
            'supplier_id'  => $supplierId,
            'company_name' => 'Test Supplier',
            'address'      => 'Jl. Test',
            'phone_number' => '08123456789',
            'bank_account' => '123-456-789'
        ]);

        $branch = Branch::create([
            'branch_name'      => 'Cabang Test',
            'branch_address'   => 'Alamat Test',
            'branch_telephone' => '0811000000',
            'is_active'        => 1
        ]);

        PurchaseOrder::create([
            'po_number'  => 'PO-001',
            'supplier_id'=> $supplierId,
            'total'      => 1000,
            'branch_id'  => $branch->id,
            'order_date' => Carbon::now()->toDateString(),
            'status'     => 'NEW'
        ]);

        $results = PurchaseOrder::getPurchaseOrderBySupplierId($supplierId);

        $this->assertIsIterable($results);
        $this->assertGreaterThan(0, $results->count(), "Expected at least one purchase order for supplier {$supplierId}");
    }
}