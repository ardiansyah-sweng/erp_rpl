<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceiptNote;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsReceiptNoteController extends Controller
{
    public function store(Request $request)
    {
        $allData = $request->all();

        if (count($allData) < 2) {
             return redirect()->back()->with('error', 'Invalid GRN data structure.');
        }

        $itemDetails = array_slice($allData, 0, -1);
        $headerData = end($allData);

        $headerValidationRules = [
            'po_number'    => 'required|string|exists:'.config('db_constants.table.po').','.config('db_constants.column.po.po_number'),
            'receipt_date' => 'required|date',
            'received_by'  => 'required|string|max:100',
        ];

        $detailValidationRules = [
            'product_id'        => 'required|string|exists:'.config('db_constants.table.item').','.config('db_constants.column.item.sku'),
            'quantity_received' => 'required|numeric|min:0',
            'notes'             => 'nullable|string|max:255',
        ];

        $headerValidator = Validator::make($headerData, $headerValidationRules);
        if ($headerValidator->fails()) {
            return redirect()->back()->withErrors($headerValidator)->withInput();
        }

        $validatedDetails = [];
        foreach ($itemDetails as $index => $item) {
            $detailValidator = Validator::make($item, $detailValidationRules);
            if ($detailValidator->fails()) {
                 return redirect()->back()->withErrors($detailValidator, 'grn_details_'.$index)->withInput();
            }
             $validatedDetails[] = $detailValidator->validated();
        }

        $validatedHeader = $headerValidator->validated();
        $grnData = array_merge(['header' => $validatedHeader], ['details' => $validatedDetails]);

        DB::beginTransaction();
        try {
            GoodsReceiptNote::addGoodsReceiptNote($grnData);
            DB::commit();
            return redirect()->back()->with('success', 'Goods Receipt Note successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GRN Creation Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create GRN: ' . $e->getMessage())->withInput();
        }
    }
}