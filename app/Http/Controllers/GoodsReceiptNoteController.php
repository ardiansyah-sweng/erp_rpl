<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, string $po_number): JsonResponse
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'receipt_date' => 'sometimes|date',
                'supplier_name' => 'sometimes|string|max:255',
                'warehouse_location' => 'sometimes|string|max:255',
                'total_quantity' => 'sometimes|numeric|min:0',
                'total_amount' => 'sometimes|numeric|min:0',
                'status' => 'sometimes|in:pending,received,cancelled',
                'notes' => 'sometimes|string|max:1000',
            ]);

            // Cek PO number tidak kosong
            if (empty($po_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'PO number is required'
                ], 400);
            }

            // Simulasi update - nanti diganti dengan GoodsReceiptNoteModel::updateByPoNumber($po_number, $validatedData)
            $mockUpdatedData = array_merge([
                'po_number' => $po_number,
                'receipt_date' => '2024-06-16',
                'supplier_name' => 'Default Supplier',
                'warehouse_location' => 'Warehouse A',
                'total_quantity' => 100,
                'total_amount' => 5000000,
                'status' => 'pending',
                'notes' => 'Default notes',
                'updated_at' => now()->toDateTimeString()
            ], $validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Goods Receipt Note updated successfully',
                'data' => $mockUpdatedData
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
