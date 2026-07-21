<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderPaymentRequest;
use App\Models\ActivityLog;
use App\Constants\ActivityLogColumns;
use App\Constants\Messages;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPayment;
use Illuminate\Http\Request;

class PurchaseOrderPaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $payments = PurchaseOrderPayment::getPayments($search);

        return view('purchase_order_payment.index', compact('payments', 'search'));
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->orderBy('order_date', 'desc')
            ->get()
            ->map(function ($po) {
                $po->paid_amount = PurchaseOrderPayment::getTotalPaid($po->po_number);
                $po->remaining_amount = $po->total - $po->paid_amount;

                return $po;
            })
            ->filter(fn ($po) => $po->remaining_amount > 0);

        return view('purchase_order_payment.create', compact('purchaseOrders'));
    }

    public function store(StorePurchaseOrderPaymentRequest $request)
    {
        $payment = PurchaseOrderPayment::addPayment($request->validated());

        ActivityLog::logActivity(
            ActivityLogColumns::ACTION_CREATE,
            ActivityLogColumns::MODULE_PO_PAYMENT,
            "Mencatat pembayaran PO '{$payment->po_number}' sebesar {$payment->amount}",
            $payment->id
        );

        return redirect()->route('po-payments.show', $payment->id)
            ->with('success', Messages::PO_PAYMENT_CREATED);
    }

    public function show($id)
    {
        $payment = PurchaseOrderPayment::with('purchaseOrder.supplier')->find($id);

        if (! $payment) {
            return abort(404, Messages::PO_PAYMENT_NOT_FOUND);
        }

        $paidAmount = PurchaseOrderPayment::getTotalPaid($payment->po_number);
        $remainingAmount = ($payment->purchaseOrder->total ?? 0) - $paidAmount;

        return view('purchase_order_payment.show', compact('payment', 'paidAmount', 'remainingAmount'));
    }
}
