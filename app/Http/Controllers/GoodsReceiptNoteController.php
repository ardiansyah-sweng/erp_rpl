<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GoodsReceiptNoteController extends Controller
{
    public function addGoodsReceiptNote(Request $request)
    {
        $rules = [
            'header.po_number'    => 'required|string|exists:'.config('db_constants.table.po', 'purchase_orders').','.config('db_constants.column.po.po_number', 'po_number'),
            'header.delivery_date' => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.product_id'  => 'required|string|exists:'.config('db_constants.table.item', 'items').','.config('db_constants.column.item.sku', 'sku'),
            'items.*.delivered_quantity' => 'required|numeric|min:0',
            'items.*.comments'    => 'nullable|string|max:255',
        ];

        $messages = [
            'header.po_number.required' => 'Purchase Order number is required.',
            'header.po_number.exists'   => 'The selected Purchase Order number is invalid.',
            'header.delivery_date.required' => 'Delivery date is required.',
            'items.required'            => 'At least one item must be provided for the GRN.',
            'items.min'                 => 'At least one item must be provided for the GRN.',
            'items.*.product_id.required' => 'Product ID is required for item #:position.',
            'items.*.product_id.exists'   => 'Product ID for item #:position is invalid.',
            'items.*.delivered_quantity.required' => 'Delivered quantity is required for item #:position.',
            'items.*.delivered_quantity.numeric' => 'Delivered quantity for item #:position must be a number.',
            'items.*.delivered_quantity.min' => 'Delivered quantity for item #:position must be at least 0.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();
        $validatedHeader = $validatedData['header'];
        $validatedDetails = $validatedData['items'];

        try {
            GoodsReceiptNote::addGoodsReceiptNote($validatedHeader, $validatedDetails);

            return redirect()->back()->with('success', 'Goods Receipt Note successfully created.');

        } catch (\Exception $e) {
            Log::error('Controller GRN Creation Failed: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to create Goods Receipt Note. Please try again or contact support.')->withInput();
        }
    }
}