<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceiptNote;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PurchaseReturnController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('keyword'));

        $returns = PurchaseReturn::with(['purchaseOrder.supplier', 'item'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('return_number', 'like', "%{$keyword}%")
                        ->orWhere('po_number', 'like', "%{$keyword}%")
                        ->orWhere('product_id', 'like', "%{$keyword}%")
                        ->orWhereHas('item', fn ($item) => $item->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('purchaseOrder.supplier', fn ($supplier) => $supplier->where('company_name', 'like', "%{$keyword}%"));
                });
            })
            ->latest('return_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('purchase_returns.index', compact('returns', 'keyword'));
    }

    public function create(Request $request)
    {
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->whereIn('po_number', GoodsReceiptNote::query()->select('po_number')->distinct())
            ->orderByDesc('order_date')
            ->get();

        $selectedPoNumber = (string) $request->query('po_number');
        $selectedPurchaseOrder = null;
        $returnableItems = collect();

        if ($selectedPoNumber !== '') {
            $selectedPurchaseOrder = PurchaseOrder::with('supplier')
                ->where('po_number', $selectedPoNumber)
                ->firstOrFail();

            $receivedItems = GoodsReceiptNote::query()
                ->where('po_number', $selectedPoNumber)
                ->select('product_id', DB::raw('SUM(delivered_quantity) as received_quantity'))
                ->groupBy('product_id')
                ->get();

            $returnableItems = $receivedItems->map(function ($receipt) use ($selectedPoNumber) {
                $item = Item::where('sku', $receipt->product_id)->first();
                $returnedQuantity = PurchaseReturn::where('po_number', $selectedPoNumber)
                    ->where('product_id', $receipt->product_id)
                    ->sum('quantity');
                $remainingReceived = max(0, (int) $receipt->received_quantity - (int) $returnedQuantity);
                $currentStock = (int) ($item?->stock_unit ?? 0);

                return (object) [
                    'sku' => $receipt->product_id,
                    'name' => $item?->name ?? 'Item tidak ditemukan',
                    'received_quantity' => (int) $receipt->received_quantity,
                    'returned_quantity' => (int) $returnedQuantity,
                    'current_stock' => $currentStock,
                    'available_quantity' => min($remainingReceived, $currentStock),
                ];
            })->filter(fn ($item) => $item->available_quantity > 0)->values();
        }

        return view('purchase_returns.create', compact(
            'purchaseOrders',
            'selectedPoNumber',
            'selectedPurchaseOrder',
            'returnableItems'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|exists:purchase_order,po_number',
            'product_id' => 'required|string|max:50',
            'return_date' => 'required|date|before_or_equal:today',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|in:Rusak,Tidak sesuai,Kelebihan kirim,Kedaluwarsa,Lainnya',
            'notes' => 'nullable|string|max:255',
        ]);

        $purchaseReturn = DB::transaction(function () use ($validated) {
            $receipts = GoodsReceiptNote::where('po_number', $validated['po_number'])
                ->where('product_id', $validated['product_id'])
                ->lockForUpdate()
                ->get();

            $receivedQuantity = (int) $receipts->sum('delivered_quantity');
            if ($receivedQuantity < 1) {
                throw ValidationException::withMessages([
                    'product_id' => 'Barang ini belum pernah diterima melalui Goods Receipt Note untuk PO tersebut.',
                ]);
            }

            $previousReturns = PurchaseReturn::where('po_number', $validated['po_number'])
                ->where('product_id', $validated['product_id'])
                ->lockForUpdate()
                ->get();
            $remainingReceived = $receivedQuantity - (int) $previousReturns->sum('quantity');

            $item = Item::where('sku', $validated['product_id'])->lockForUpdate()->first();
            if (! $item) {
                throw ValidationException::withMessages([
                    'product_id' => 'Item dengan SKU tersebut tidak ditemukan.',
                ]);
            }

            $availableQuantity = min($remainingReceived, (int) $item->stock_unit);
            if ((int) $validated['quantity'] > $availableQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Jumlah retur maksimal {$availableQuantity} unit berdasarkan penerimaan dan stok saat ini.",
                ]);
            }

            $oldStock = (int) $item->stock_unit;
            $newStock = $oldStock - (int) $validated['quantity'];
            $returnNumber = $this->generateReturnNumber();

            $purchaseReturn = PurchaseReturn::create([
                ...$validated,
                'return_number' => $returnNumber,
            ]);

            $item->stock_unit = $newStock;
            $item->save();

            DB::table(config('db_constants.table.log_matory', 'log_material_inventory'))->insert([
                'log_id' => "Return {$returnNumber}",
                'sku' => $validated['product_id'],
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $purchaseReturn;
        });

        return redirect()
            ->route('purchase-returns.show', $purchaseReturn)
            ->with('success', 'Retur barang berhasil dicatat dan stok telah diperbarui.');
    }

    public function show(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load(['purchaseOrder.supplier', 'item']);

        return view('purchase_returns.show', compact('purchaseReturn'));
    }

    private function generateReturnNumber(): string
    {
        do {
            $number = 'RT-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
        } while (PurchaseReturn::where('return_number', $number)->exists());

        return $number;
    }
}
