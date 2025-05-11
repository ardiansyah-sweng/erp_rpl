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
             return redirect()->back()->with('error', 'Invalid GRN data structure.');
        }

        $itemDetailsInput = array_slice($allData, 0, -1);
        $headerDataInput = end($allData);

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

        $validatedHeader = $headerValidator->validated();

        $colGrn = config('db_constants.column.grn');

        $grnHeaderDataForDb = [];
        foreach ($validatedHeader as $inputKey => $value) {
            if (isset($colGrn[$inputKey])) {
                $grnHeaderDataForDb[$colGrn[$inputKey]] = $value;
            } else {
                Log::warning("GRN Store: Kunci input '{$inputKey}' tidak ditemukan dalam mapping db_constants.column.grn.");
            }
        }
        
        $payloadForModel = $grnHeaderDataForDb;
        $payloadForModel['details'] = $validatedDetails;

        DB::beginTransaction();
        try {
            GoodsReceiptNote::addGoodsReceiptNote($payloadForModel);
            DB::commit();
            return redirect()->back()->with('success', 'Goods Receipt Note successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GRN Creation Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create GRN. Details: ' . $e->getMessage())->withInput();
        }
    }
}