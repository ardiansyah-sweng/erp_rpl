<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;

class PurchaseOrderRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_orders_index_returns_ok()
    {
        $supplier = Supplier::factory()->create();

        PurchaseOrder::create([
            'po_number' => 'PO000001',
            'branch_id' => 1,
            'supplier_id' => $supplier->supplier_id,
            'order_date' => now()->toDateString(),
            'total' => 10000,
        ]);

        $response = $this->get('/purchase_orders');
        $response->assertStatus(200);
    }

    public function test_get_purchase_order_by_id_returns_ok()
    {
        $supplier = Supplier::factory()->create();

        $po = PurchaseOrder::create([
            'po_number' => 'PO000002',
            'branch_id' => 1,
            'supplier_id' => $supplier->supplier_id,
            'order_date' => now()->toDateString(),
            'total' => 20000,
        ]);

        $response = $this->get('/purchase_orders/' . $po->id);
        $response->assertStatus(200);
    }

    public function test_search_purchase_orders_returns_ok()
    {
        $supplier = Supplier::factory()->create(['company_name' => 'TesSupplier']);

        PurchaseOrder::create([
            'po_number' => 'PO000003',
            'branch_id' => 1,
            'supplier_id' => $supplier->supplier_id,
            'order_date' => now()->toDateString(),
            'total' => 30000,
        ]);

        $response = $this->get('/purchase-orders/search?keyword=TesSupplier');
        $response->assertStatus(200);
    }
}
