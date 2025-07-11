<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderEmail;
use App\Models\PurchaseOrder;

class SendSamplePurchaseOrderTest extends TestCase
{
    public function test_send_sample_purchase_order_email_success()
    {
        Mail::fake();

        // Ambil PO dari database yang sudah ada
        $po = PurchaseOrder::with('supplier', 'details')->first();

        // Kalau tidak ada PO atau detail kosong, skip test
        if (!$po || $po->details->isEmpty()) {
            $this->markTestSkipped('PO tidak ditemukan atau tidak punya detail.');
        }

        // Akses route pengiriman email
        $response = $this->get(route('purchase_orders.email.sample'));

        $response->assertRedirect();

        Mail::assertSent(PurchaseOrderEmail::class, function ($mail) {
            return $mail->hasTo('tes@dummy.com');
        });
    }

    public function test_send_sample_purchase_order_email_fail_when_no_po()
    {
        Mail::fake();

        // Kosongkan tabel purchase_order sementara
        \App\Models\PurchaseOrder::truncate();

        $response = $this->get(route('purchase_orders.email.sample'));

        $response->assertRedirect();

        Mail::assertNothingSent();
    }
}
