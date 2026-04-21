<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseOrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_purchase_order_returns_redirect()
    {
        // Sesuaikan URL jika route berbeda
        $url = '/purchase_orders/store';

        $payload = [
            ["po_number" => "PO-2026-001", "sku" => "ITEM001", "qty" => 2, "amount" => 100.0],
            ["po_number" => "PO-2026-001", "branch_id" => 1, "supplier_id" => "SUP01", "total" => 200.0, "order_date" => "2026-04-20"]
        ];

        $response = $this->postJson($url, $payload);

        // controller saat ini redirect()->back() pada sukses => status 302
        $response->assertStatus(302);
        // bila ingin cek session message
        $response->assertSessionHas('success');
    }
}