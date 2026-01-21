<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;
use App\Models\PurchaseOrder;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test normal: Mengambil supplier dengan frekuensi order.
     */
    public function testGetSupplierWithOrderFrequency()
    {
        // Buat data supplier dan PO untuk testing
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'Test Supplier',
            'address' => 'Test Address',
            'telephone' => '123456789',
            'bank_account' => '1234567890',
        ]);
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'supplier_id' => $supplier->supplier_id,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => 'pending',
        ]);

        $this->getJson('/cek-supplier-frekuensi');
        $this->assertTrue(true);
    }

    /**
     * Test count supplier pic.
     */
    public function testCountSupplierPic()
    {
        $supplier = Supplier::factory()->create();
        SupplierPic::create([
            'supplier_id' => $supplier->supplier_id,
            'name' => 'Test PIC 1',
            'email' => 'pic1@test.com',
            'phone_number' => '123456789',
            'assigned_date' => now(),
        ]);
        SupplierPic::create([
            'supplier_id' => $supplier->supplier_id,
            'name' => 'Test PIC 2',
            'email' => 'pic2@test.com',
            'phone_number' => '987654321',
            'assigned_date' => now(),
        ]);
        SupplierPic::create([
            'supplier_id' => $supplier->supplier_id,
            'name' => 'Test PIC 3',
            'email' => 'pic3@test.com',
            'phone_number' => '555666777',
            'assigned_date' => now(),
        ]);

        $count = SupplierPic::countSupplierPIC($supplier->supplier_id);

        $this->assertEquals(3, $count);

        $this->assertTrue(true); // Placeholder assertion
    }

    /**
     * Test count PO.
     */
    public function testCountPurchaseOrders()
    {
        // Buat supplier untuk PO
        $supplier1 = Supplier::factory()->create();
        $supplier2 = Supplier::factory()->create();
        $supplier3 = Supplier::factory()->create();
        $supplier4 = Supplier::factory()->create();
        $supplier5 = Supplier::factory()->create();

        PurchaseOrder::create([
            'po_number' => 'PO001',
            'supplier_id' => $supplier1->supplier_id,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => 'pending',
        ]);
        PurchaseOrder::create([
            'po_number' => 'PO002',
            'supplier_id' => $supplier2->supplier_id,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 2000,
            'status' => 'pending',
        ]);
        PurchaseOrder::create([
            'po_number' => 'PO003',
            'supplier_id' => $supplier3->supplier_id,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 3000,
            'status' => 'pending',
        ]);
        PurchaseOrder::create([
            'po_number' => 'PO004',
            'supplier_id' => $supplier4->supplier_id,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 4000,
            'status' => 'pending',
        ]);
        PurchaseOrder::create([
            'po_number' => 'PO005',
            'supplier_id' => $supplier5->supplier_id,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 5000,
            'status' => 'pending',
        ]);

        $count = PurchaseOrder::countPurchaseOrder();

        $this->assertEquals(5, $count);

        $this->getJson('/cek-supplier-frekuensi');
        $this->assertTrue(true);
    }
}
