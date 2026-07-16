<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grn_id' => 'required|integer|exists:goods_receipt_note,id',
            'return_date' => 'required|date|before_or_equal:today',
            'return_quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'bukti_lampiran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'grn_id.required' => 'Goods Receipt Note wajib dipilih.',
            'grn_id.exists' => 'Goods Receipt Note tidak ditemukan.',
            'return_date.required' => 'Tanggal return wajib diisi.',
            'return_date.before_or_equal' => 'Tanggal return tidak boleh melebihi hari ini.',
            'return_quantity.required' => 'Jumlah return wajib diisi.',
            'return_quantity.min' => 'Jumlah return minimal 1 unit.',
            'reason.required' => 'Alasan return wajib diisi.',
            'reason.max' => 'Alasan return maksimal 255 karakter.',
            'bukti_lampiran.image' => 'File harus berupa gambar.',
            'bukti_lampiran.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'bukti_lampiran.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
