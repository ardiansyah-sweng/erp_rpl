<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\PurchaseOrder;

class AddPurchaseOrderModelTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * A basic feature test example.
     */
    public function test_it_can_add_a_purchase_order_from_request_data(): void
    {
        // Ambil 1 supplier dan 1 branch dari tabel yang sudah ada
        $branch = Branch::inRandomOrder()->first();
        $supplier = Supplier::inRandomOrder()->first();

        // Ambil PO Number terakhir
        $lastPO = PurchaseOrder::orderByRaw('CAST(SUBSTRING(po_number, 3) AS UNSIGNED) DESC')->first();
        
        if ($lastPO) {
            // Ambil angka dari po_number terakhir
            $lastNumber = (int) substr($lastPO->po_number, 2); // hasilnya: 34
            $newNumber = $lastNumber + 1;                      // hasilnya: 35
            $newPoNumber = 'PO' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // hasil: PO0035
        } else {
            // Jika belum ada data, mulai dari PO0001
            $newPoNumber = 'PO0001';
        }

        $start_date = Carbon::parse('2025-01-01');
        $end_date = Carbon::parse('2025-04-10');
        $orderDate = Carbon::parse($start_date->format('Y-m-d'))->addDays(rand(0, $start_date->diffInDays($end_date)))->format('Y-m-d');

        $total = 0;

        $postData = [
            'po_number' => $newPoNumber,
            'branch_id' => $branch->id,
            'supplier_id' => $supplier->supplier_id,
            'total' => $total,
            'order_date' => $orderDate,
        ];

        $request = new Request($postData);
        PurchaseOrder::addPurchaseOrder($request);

        $this->assertDatabaseHas('purchase_order', [
            'po_number' => $newPoNumber,
        ]);

    }
}
