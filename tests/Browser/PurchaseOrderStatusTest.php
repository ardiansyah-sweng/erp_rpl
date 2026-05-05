<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseOrderStatusTest extends DuskTestCase
{
    protected $supplierId = 'TEST01';
    protected $poDraftId = 'PO-DRF';
    protected $poAppId = 'PO-APP';

    public function test_user_can_filter_purchase_order_by_status()
    {
        // 1. Pastikan data ada
        $this->ensureDataExists();

        // 2. Browse halaman tanpa login
        $this->browse(function (Browser $browser) {
            $browser->visit('/purchase-order/status/Draft')
                    ->assertPathIs('/purchase-order/status/Draft')
                    ->assertSee($this->poDraftId)
                    ->assertDontSee($this->poAppId);
        });

        // 3. Cleanup PO dummy
        PurchaseOrder::where('po_number', $this->poDraftId)->delete();
        PurchaseOrder::where('po_number', $this->poAppId)->delete();
    }

    private function ensureDataExists()
    {
        // A. Pastikan BRANCH ada
        $branch = DB::table('branches')->where('id', 1)->first();
        if (!$branch) {
            DB::table('branches')->insert([
                'id' => 1,
                'branch_name' => 'Cabang Test Otomatis',
                'branch_address' => 'Jl. Test',
                'branch_telephone' => '081234567',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // B. Pastikan SUPPLIER ada
        $supplier = Supplier::where('supplier_id', $this->supplierId)->first();
        if (!$supplier) {
            $supplier = new Supplier();
            $supplier->supplier_id = $this->supplierId;
            $supplier->company_name = 'PT Vendor Test Otomatis';
            $supplier->address = 'Jl. Auto Generate';
            $supplier->telephone = '0812345678';
            $supplier->bank_account = '123-456-789';
            $supplier->save();
        }

        // C. Hapus PO lama kalau ada
        PurchaseOrder::where('po_number', $this->poDraftId)->delete();
        PurchaseOrder::where('po_number', $this->poAppId)->delete();

        // D. Buat PO Draft
        $po1 = new PurchaseOrder();
        $po1->po_number = $this->poDraftId;
        $po1->supplier_id = $this->supplierId;
        $po1->branch_id = 1;
        $po1->total = 100000;
        $po1->order_date = Carbon::now();
        $po1->status = 'Draft';
        $po1->save();

        // E. Buat PO Approved
        $po2 = new PurchaseOrder();
        $po2->po_number = $this->poAppId;
        $po2->supplier_id = $this->supplierId;
        $po2->branch_id = 1;
        $po2->total = 200000;
        $po2->order_date = Carbon::now();
        $po2->status = 'Approved';
        $po2->save();
    }
}
