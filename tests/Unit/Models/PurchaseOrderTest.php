<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class PurchaseOrderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * ===========================
     *  TEST countPurchaseOrder()
     * ===========================
     */

    public function testCountPurchaseOrderWithMultipleRecords()
    {
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'branch_id' => 1,
            'supplier_id' => 1,
            'order_date' => now(),
            'total' => 1000,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO002',
            'branch_id' => 1,
            'supplier_id' => 2,
            'order_date' => now(),
            'total' => 2000,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO003',
            'branch_id' => 2,
            'supplier_id' => 1,
            'order_date' => now(),
            'total' => 1500,
        ]);

        $count = PurchaseOrder::countPurchaseOrder();
        $this->assertEquals(3, $count);
    }

    public function testCountPurchaseOrderWithZeroRecords()
    {
        $count = PurchaseOrder::countPurchaseOrder();
        $this->assertEquals(0, $count);
    }

    public function testCountPurchaseOrderWithSingleRecord()
    {
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'branch_id' => 1,
            'supplier_id' => 1,
            'order_date' => now(),
            'total' => 1000,
        ]);

        $count = PurchaseOrder::countPurchaseOrder();
        $this->assertEquals(1, $count);
    }


    /**
     * ======================================
     *  TEST getReportBySupplierAndDate()
     * ======================================
     */

    public function test_get_report_returns_collection()
    {
        $supplier = Supplier::first();

        if (!$supplier) {
            $this->markTestSkipped("Tidak ada supplier di database");
        }

        $result = PurchaseOrder::getReportBySupplierAndDate(
            $supplier->supplier_id,
            '2024-01-01',
            '2024-12-31'
        );

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    public function test_get_report_with_invalid_supplier_returns_empty()
    {
        $result = PurchaseOrder::getReportBySupplierAndDate(
            'INVALID_SUPPLIER_ID',
            '2024-01-01',
            '2024-12-31'
        );

        $this->assertEquals(0, $result->count());
    }

    public function test_get_report_relations_loaded()
    {
        $supplier = Supplier::first();

        if (!$supplier) {
            $this->markTestSkipped("Tidak ada supplier");
        }

        $result = PurchaseOrder::getReportBySupplierAndDate(
            $supplier->supplier_id,
            '2024-01-01',
            '2024-12-31'
        );

        if ($result->count() > 0) {
            $po = $result->first();
            $this->assertTrue($po->relationLoaded('supplier'));
            $this->assertTrue($po->relationLoaded('details'));
        }

        $this->assertTrue(true);
    }

    public function test_get_report_order_sorted_desc()
    {
        $supplier = Supplier::first();

        if (!$supplier) {
            $this->markTestSkipped("Tidak ada supplier");
        }

        $result = PurchaseOrder::getReportBySupplierAndDate(
            $supplier->supplier_id,
            '2024-01-01',
            '2024-12-31'
        );

        if ($result->count() > 1) {
            $dates = $result->pluck('order_date')->toArray();
            $sorted = $result->pluck('order_date')->sortDesc()->values()->toArray();
            $this->assertEquals($sorted, $dates);
        }

        $this->assertTrue(true);
    }

    public function test_get_report_by_date_range_filter()
    {
        $supplier = Supplier::first();

        if (!$supplier) {
            $this->markTestSkipped("Tidak ada supplier");
        }

        $result = PurchaseOrder::getReportBySupplierAndDate(
            $supplier->supplier_id,
            '2024-06-01',
            '2024-06-30'
        );

        foreach ($result as $po) {
            $orderDate = Carbon::parse($po->order_date);
            $this->assertTrue($orderDate->between('2024-06-01', '2024-06-30'));
        }

        $this->assertTrue(true);
    }

    public function test_get_report_filter_by_supplier()
    {
        $supplier = Supplier::first();

        if (!$supplier) {
            $this->markTestSkipped("Tidak ada supplier");
        }

        $result = PurchaseOrder::getReportBySupplierAndDate(
            $supplier->supplier_id,
            '2024-01-01',
            '2024-12-31'
        );

        foreach ($result as $po) {
            $this->assertEquals($supplier->supplier_id, $po->supplier_id);
        }

        $this->assertTrue(true);
    }
}
