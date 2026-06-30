<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Constants\Messages;
use App\Helpers\EncryptionHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Enums\POStatus;

class PurchaseOrderActionsFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        // Ensure branches table has at least one branch
        DB::table('branches')->insertOrIgnore([
            'id' => 1,
            'branch_name' => 'Cabang Test',
            'branch_address' => 'Jl. Test',
            'branch_telephone' => '081234567',
        ]);

        // Ensure supplier table exists and has the test supplier
        Supplier::create([
            'supplier_id' => 'SUP123',
            'company_name' => 'PT Test Supplier',
            'address' => 'Jl. Supplier',
            'telephone' => '089876543',
            'bank_account' => '987-654-321',
        ]);
    }

    /**
     * Test deletePurchaseOrder endpoint deletes PO and redirects back.
     */
    public function test_controller_deletes_purchase_order()
    {
        // Arrange: Create a purchase order in DB
        PurchaseOrder::create([
            'po_number' => 'PO999',
            'supplier_id' => 'SUP123',
            'branch_id' => 1,
            'total' => 100000,
            'order_date' => now()->toDateString(),
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrderDetail::create([
            'po_number' => 'PO999',
            'product_id' => 'PROD-1',
            'quantity' => 10,
            'amount' => 100000,
        ]);

        $encryptedId = EncryptionHelper::encrypt('PO999');

        // Act: Send DELETE request to endpoint
        $response = $this->delete(route('purchase_orders.delete', $encryptedId));

        // Assert: Redirect to list, and DB checks
        $response->assertStatus(302);
        $response->assertRedirect(route('purchase.orders'));
        $response->assertSessionHas('success', Messages::PO_DELETED);

        $this->assertDatabaseMissing('purchase_order', ['po_number' => 'PO999']);
        $this->assertDatabaseMissing('purchase_order_detail', ['po_number' => 'PO999']);
    }

    /**
     * Test updateStatus endpoint updates status successfully.
     */
    public function test_controller_updates_purchase_order_status()
    {
        // Arrange: Create PO
        PurchaseOrder::create([
            'po_number' => 'PO888',
            'supplier_id' => 'SUP123',
            'branch_id' => 1,
            'total' => 200000,
            'order_date' => now()->toDateString(),
            'status' => POStatus::Draft->value,
        ]);

        $encryptedId = EncryptionHelper::encrypt('PO888');

        // Act: Send status update request
        $response = $this->post(route('purchase_orders.update_status', $encryptedId), [
            'status' => POStatus::Submitted->value,
        ]);

        // Assert: Redirect back and state changes in DB
        $response->assertStatus(302);
        $response->assertSessionHas('success', Messages::PO_STATUS_UPDATED);

        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO888',
            'status' => POStatus::Submitted->value,
        ]);
    }

    /**
     * Test getPurchaseOrderByStatus endpoint.
     */
    public function test_controller_filters_purchase_orders_by_status()
    {
        // Create a draft PO
        PurchaseOrder::create([
            'po_number' => 'PO111',
            'supplier_id' => 'SUP123',
            'branch_id' => 1,
            'total' => 100000,
            'order_date' => now()->toDateString(),
            'status' => 'Draft',
        ]);

        // Create an approved PO
        PurchaseOrder::create([
            'po_number' => 'PO222',
            'supplier_id' => 'SUP123',
            'branch_id' => 1,
            'total' => 200000,
            'order_date' => now()->toDateString(),
            'status' => 'Approved',
        ]);

        // Request status 'Draft'
        $response = $this->get('/purchase-order/status/Draft');

        $response->assertStatus(200);
        $response->assertSee('PO111');
        $response->assertDontSee('PO222');
    }
}
