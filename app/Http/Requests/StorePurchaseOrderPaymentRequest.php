<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'po_number' => 'required|string|exists:purchase_order,po_number',
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|integer|min:1',
            'method' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'po_number.required' => 'Purchase Order wajib dipilih.',
            'po_number.exists' => 'Purchase Order tidak ditemukan.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'payment_date.before_or_equal' => 'Tanggal pembayaran tidak boleh melebihi hari ini.',
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.min' => 'Jumlah pembayaran minimal 1.',
            'method.max' => 'Metode pembayaran maksimal 50 karakter.',
            'note.max' => 'Catatan maksimal 255 karakter.',
        ];
    }
}
