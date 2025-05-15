<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
             return redirect()->back()->with('error', 'Invalid GRN data structure. Expecting item details and a header.');
        }

        $itemDetailsInput = array_slice($allData, 0, -1);
        $headerDataInput = end($allData);

        $headerValidationRules = [
            'po_number'    => 'required|string|exists:'.config('db_constants.table.po', 'purchase_orders').','.config('db_constants.column.po.po_number', 'po_number'),
            'delivery_date' => 'required|date',
        ];

        $detailValidationRules = [
            'product_id'        => 'required|string|exists:'.config('db_constants.table.item', 'items').','.config('db_constants.column.item.sku', 'sku'),
            'delivered_quantity' => 'required|numeric|min:0',
            'comments'             => 'nullable|string|max:255',
        ];

        $headerValidator = Validator::make($headerDataInput, $headerValidationRules);
        if ($headerValidator->fails()) {
            return redirect()->back()->withErrors($headerValidator)->withInput();
        }

        $validatedDetails = [];
        foreach ($itemDetailsInput as $index => $item) {
            $detailValidator = Validator::make($item, $detailValidationRules);
            if ($detailValidator->fails()) {
                 return redirect()->back()->withErrors($detailValidator, 'grn_details_'.$index)->withInput();
            }
             $validatedDetails[] = $detailValidator->validated();
        }

        if (empty($validatedDetails)) {
            return redirect()->back()->with('error', 'No item details provided for GRN.')->withInput();
        }

        $validatedHeader = $headerValidator->validated();

        DB::beginTransaction();
        try {
            foreach ($validatedDetails as $itemDetail) {
                $createData = [];
                $createData['po_number'] = $validatedHeader['po_number'];
                $createData['delivery_date'] = $validatedHeader['delivery_date'];

                $createData['product_id'] = $itemDetail['product_id'];
                $createData['delivered_quantity'] = $itemDetail['delivered_quantity'];
                $createData['comments'] = $itemDetail['comments'] ?? null;
                
                GoodsReceiptNote::create($createData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Goods Receipt Note successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GRN Creation Failed: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to create GRN. Details: ' . $e->getMessage())->withInput();
        }
    }
}