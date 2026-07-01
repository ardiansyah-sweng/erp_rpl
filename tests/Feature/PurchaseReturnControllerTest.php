<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchaseReturnControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index_page_can_be_opened(): void
    {
        $this->get(route('purchase-returns.index'))
            ->assertOk()
            ->assertSee('Retur Barang');
    }

    public function test_create_page_uses_existing_supplier_table(): void
    {
        $this->get(route('purchase-returns.create'))
            ->assertOk()
            ->assertSee('Tambah Retur Barang');
    }

    public function test_return_reduces_stock_and_creates_inventory_log(): void
    {
        [$poNumber, $sku] = $this->createReceivedItem();
        $stockBeforeReturn = (int) DB::table('items')->where('sku', $sku)->value('stock_unit');

        $response = $this->post(route('purchase-returns.store'), [
            'po_number' => $poNumber,
            'product_id' => $sku,
            'return_date' => now()->format('Y-m-d'),
            'quantity' => 3,
            'reason' => 'Rusak',
            'notes' => 'Kemasan rusak saat diterima.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_returns', [
            'po_number' => $poNumber,
            'product_id' => $sku,
            'quantity' => 3,
            'reason' => 'Rusak',
        ]);
        $this->assertDatabaseHas('items', [
            'sku' => $sku,
            'stock_unit' => $stockBeforeReturn - 3,
        ]);
        $this->assertDatabaseHas('log_material_inventory', [
            'sku' => $sku,
            'old_stock' => $stockBeforeReturn,
            'new_stock' => $stockBeforeReturn - 3,
        ]);
    }

    public function test_return_cannot_exceed_received_quantity(): void
    {
        [$poNumber, $sku] = $this->createReceivedItem();
        $stockBeforeReturn = (int) DB::table('items')->where('sku', $sku)->value('stock_unit');

        $this->from(route('purchase-returns.create', ['po_number' => $poNumber]))
            ->post(route('purchase-returns.store'), [
                'po_number' => $poNumber,
                'product_id' => $sku,
                'return_date' => now()->format('Y-m-d'),
                'quantity' => 6,
                'reason' => 'Rusak',
            ])
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseMissing('purchase_returns', [
            'po_number' => $poNumber,
            'product_id' => $sku,
        ]);
        $this->assertSame(
            $stockBeforeReturn,
            (int) DB::table('items')->where('sku', $sku)->value('stock_unit')
        );
    }

    private function createReceivedItem(): array
    {
        $suffix = strtoupper(substr(bin2hex(random_bytes(4)), 0, 4));
        $poNumber = 'T' . $suffix . 'X';
        $sku = 'RET-' . $suffix;

        DB::table('purchase_order')->insert([
            'po_number' => $poNumber,
            'supplier_id' => 'SUP999',
            'total' => 50000,
            'branch_id' => 1,
            'order_date' => now()->format('Y-m-d'),
            'status' => 'Fully Delivered',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('items')->insert([
            'product_id' => 'R999',
            'sku' => $sku,
            'name' => 'Item Uji Retur',
            'measurement' => 'PCS',
            'base_price' => 10000,
            'selling_price' => 12000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('goods_receipt_note')->insert([
            'po_number' => $poNumber,
            'product_id' => $sku,
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 5,
            'comments' => 'Data uji retur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$poNumber, $sku];
    }
}
