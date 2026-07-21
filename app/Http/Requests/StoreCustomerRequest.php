<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CustomerColumns;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            CustomerColumns::NAME => 'required|string|min:3|max:100|unique:customers,customer_name',
            CustomerColumns::ADDRESS => 'nullable|string|max:255',
            CustomerColumns::PHONE => 'required|string|min:3|max:30',
            CustomerColumns::IS_ACTIVE => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            CustomerColumns::NAME . '.required' => 'Nama pelanggan wajib diisi.',
            CustomerColumns::NAME . '.string' => 'Nama pelanggan harus berupa teks.',
            CustomerColumns::NAME . '.min' => 'Nama pelanggan minimal 3 karakter.',
            CustomerColumns::NAME . '.max' => 'Nama pelanggan maksimal 100 karakter.',
            CustomerColumns::NAME . '.unique' => 'Nama pelanggan sudah ada, silakan gunakan nama lain.',

            CustomerColumns::ADDRESS . '.string' => 'Alamat pelanggan harus berupa teks.',
            CustomerColumns::ADDRESS . '.max' => 'Alamat pelanggan maksimal 255 karakter.',

            CustomerColumns::PHONE . '.required' => 'Telepon pelanggan wajib diisi.',
            CustomerColumns::PHONE . '.string' => 'Telepon pelanggan harus berupa teks.',
            CustomerColumns::PHONE . '.min' => 'Telepon pelanggan minimal 3 karakter.',
            CustomerColumns::PHONE . '.max' => 'Telepon pelanggan maksimal 30 karakter.',
        ];
    }
}
