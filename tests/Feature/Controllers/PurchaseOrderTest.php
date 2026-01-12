<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PurchaseOrder;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: Mengetes via Controller
     * Sesuai instruksi: Memanggil addPurchaseOrder() dari PurchaseOrderController
     */
    public function test_bisa_menambahkan_purchase_order_baru_via_controller()
    {
        $this->withoutMiddleware();

        $data = [
            [
                'po_number' => 'PO01',
                'sku'       => 'SKU01',
                'qty'       => 10,
                'amount'    => 5000
            ],
            [
                'po_number'   => 'PO01',
                'branch_id'   => 1,
                'supplier_id' => 'SUP01',
                'total'       => 50000,
                'order_date'  => '2026-01-12',
            ]
        ];

        $response = $this->post(route('purchase_orders.add'), $data);

        $response->assertStatus(302);

        // DISINI PERBAIKANNYA: Nama tabel harus 'purchase_order' (tanpa s)
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO01'
        ]);
    }

    /**
     * TEST 2: Mengetes via Model Langsung
     * Sesuai instruksi: "insert table dengan memanggil addPurchaseOrder() dari PurchaseOrderModel"
     */
    public function test_insert_table_via_purchase_order_model()
    {
        $allData = [
            [
                'po_number' => 'PO02',
                'sku'       => 'SKU02',
                'qty'       => 5,
                'amount'    => 2000
            ],
            [
                'po_number'   => 'PO02',
                'branch_id'   => 1,
                'supplier_id' => 'SUP01',
                'total'       => 10000,
                'order_date'  => '2026-01-12',
            ]
        ];

        PurchaseOrder::addPurchaseOrder($allData);

        // DISINI PERBAIKANNYA: Nama tabel harus 'purchase_order' (tanpa s)
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO02'
        ]);
    }
}