<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Supplier;
use App\Models\PurchaseOrder; // Pastikan model ini sudah dibuat
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPurchaseOrderBySupplierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test mendapatkan Purchase Order berdasarkan Supplier ID.
     */
    public function test_get_purchase_order_by_supplier_id_success(): void
    {
        // 1. Buat data Supplier dengan field wajib agar tidak error 'default value'
        // Belajar dari error sebelumnya:
        $supplier = Supplier::create([
            'supplier_id'   => 'S1', // Gunakan ID singkat agar tidak 'Data too long'
            'company_name'  => 'PT Baja Jaya',
            'name'          => 'Agus Setiawan',
            'address'       => 'Jl. Industri No 5',
            'telephone'     => '0219988',
            'bank_account'  => '776655',
        ]);

        // 2. Buat data Purchase Order yang terhubung ke supplier tersebut
        // Sesuaikan field ini dengan PurchaseOrderModel Anda
        PurchaseOrder::create([
            'supplier_id' => $supplier->supplier_id,
            'po_number'   => 'PO-001',
            'branch_id'   => 1,
            'order_date'  => now(),
            'total'       => 1000000,
            'status'      => 'pending'
        ]);

        // 3. Panggil endpoint (Pastikan route di api.php sudah benar)
        // Gunakan supplier_id ('S1') sebagai parameter
        $response = $this->get("/api/purchase-order/{$supplier->supplier_id}");

        // 4. Verifikasi hasil
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'po_number' => 'PO-001'
        ]);
    }

    /**
     * Test jika supplier tidak ditemukan atau tidak punya PO.
     */
    public function test_get_purchase_order_not_found(): void
    {
        // Mencari ID yang tidak ada di database
        $response = $this->get("/api/purchase-order/KODE-SALAH");

        $response->assertStatus(404);
    }
}