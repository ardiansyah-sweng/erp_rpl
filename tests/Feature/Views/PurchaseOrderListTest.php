<?php

namespace Tests\Feature\Views;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Models\PurchaseOrder;

class PurchaseOrderListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function purchase_order_list_route_renders_view_and_shows_data()
    {
        // Arrange: create supplier and purchase order
        // Use direct assignment to ensure 'telephone' column (non-fillable name) is set
        $supplier = new Supplier();
        $supplier->supplier_id = 'SUP001';
        $supplier->company_name = 'ACME SUPPLY';
        $supplier->address = 'Jl. Testing';
        $supplier->telephone = '08123456789';
        $supplier->bank_account = '1234567890';
        $supplier->save();

        $po = PurchaseOrder::create([
            // purchase_order.po_number column is char(6) in migration
            'po_number' => 'PO0001',
            'supplier_id' => $supplier->supplier_id,
            'total' => 100000,
            'branch_id' => 1,
            'order_date' => now()->toDateString(),
            'status' => 'Submitted'
        ]);

        // Act: call the route
        $response = $this->get(route('purchase.orders'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('purchase_orders.list');
        $response->assertSee('PO Number');
        $response->assertSee('Supplier');
    $response->assertSee('PO0001');
        $response->assertSee('ACME SUPPLY');
    }
}
