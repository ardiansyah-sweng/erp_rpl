<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoodsReturnRequest;
use App\Models\GoodsReceiptNote;
use App\Models\GoodsReturn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodsReturnController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $goodsReturns = GoodsReturn::getGoodsReturns($search);

        return view('goods_return.index', compact('goodsReturns', 'search'));
    }

    public function create()
    {
        $receiptNotes = GoodsReceiptNote::with('item')
            ->withSum('goodsReturns as returned_quantity', 'return_quantity')
            ->orderBy('delivery_date', 'desc')
            ->get()
            ->map(function ($receiptNote) {
                $remainingQuantity = $receiptNote->delivered_quantity - ($receiptNote->returned_quantity ?? 0);
                $currentStock = $receiptNote->item?->stock_unit ?? 0;
                $receiptNote->available_return_quantity = min($remainingQuantity, $currentStock);

                return $receiptNote;
            })
            ->filter(function ($receiptNote) {
                return $receiptNote->available_return_quantity > 0;
            });

        return view('goods_return.create', compact('receiptNotes'));
    }

    public function store(Request $request) // atau StoreGoodsReturnRequest $request
    {
        // 1. Validasi input
        $request->validate([
            'grn_id' => 'required',
            'return_date' => 'required|date',
            'return_quantity' => 'required|integer',
            'reason' => 'required|string',
            'bukti_lampiran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Siapkan data
        $data = $request->except('bukti_lampiran');

        if ($request->hasFile('bukti_lampiran')) {
            $path = $request->file('bukti_lampiran')->store('bukti_returns', 'public');
            $data['bukti_lampiran'] = $path;
        }

        // 3. PANGGIL METHOD DI MODEL (Bukan self::create biasa!)
        // Pastikan memanggil method addGoodsReturn agar po_number dan stok terproses
        $goodsReturn = GoodsReturn::addGoodsReturn($data);

        return redirect()->route('goods-returns.show', $goodsReturn->id)
            ->with('success', 'Return barang berhasil disimpan dan stok telah diperbarui.');
    }

    public function showImage($filename)
    {
        $path = 'bukti_returns/' . $filename;
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        return response()->file(storage_path('app/public/' . $path));
    }

    public function show($id)
    {
        $goodsReturn = GoodsReturn::with([
            'goodsReceiptNote',
            'item',
            'purchaseOrder.supplier',
        ])->findOrFail($id);

        return view('goods_return.show', compact('goodsReturn'));
    }

    public function printPdf($id)
    {
        $goodsReturn = GoodsReturn::with([
            'goodsReceiptNote',
            'item',
            'purchaseOrder.supplier',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('goods_return.pdf', [
            'goodsReturn' => $goodsReturn,
            'generatedAt' => now()->format('d/m/Y H:i:s'),
        ]);

        return $pdf->stream('return-barang-' . $goodsReturn->return_number . '.pdf');
    }
}
