<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\WarehouseColumns;

class StoreWarehouseRequest extends FormRequest
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
            WarehouseColumns::NAME => 'required|string|min:3|max:50|unique:warehouses,warehouse_name',
            WarehouseColumns::ADDRESS => 'required|string|min:3|max:100',
            WarehouseColumns::PHONE => 'required|string|min:3|max:30',
            WarehouseColumns::IS_ACTIVE => 'boolean',
            WarehouseColumns::IS_RM_WAREHOUSE => 'boolean',
            WarehouseColumns::IS_FG_WAREHOUSE => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            WarehouseColumns::NAME . '.required' => 'Nama gudang wajib diisi.',
            WarehouseColumns::NAME . '.string' => 'Nama gudang harus berupa teks.',
            WarehouseColumns::NAME . '.min' => 'Nama gudang minimal 3 karakter.',
            WarehouseColumns::NAME . '.max' => 'Nama gudang maksimal 50 karakter.',
            WarehouseColumns::NAME . '.unique' => 'Nama gudang sudah ada, silakan gunakan nama lain.',

            WarehouseColumns::ADDRESS . '.required' => 'Alamat gudang wajib diisi.',
            WarehouseColumns::ADDRESS . '.string' => 'Alamat gudang harus berupa teks.',
            WarehouseColumns::ADDRESS . '.min' => 'Alamat gudang minimal 3 karakter.',
            WarehouseColumns::ADDRESS . '.max' => 'Alamat gudang maksimal 100 karakter.',

            WarehouseColumns::PHONE . '.required' => 'Telepon gudang wajib diisi.',
            WarehouseColumns::PHONE . '.string' => 'Telepon gudang harus berupa teks.',
            WarehouseColumns::PHONE . '.min' => 'Telepon gudang minimal 3 karakter.',
            WarehouseColumns::PHONE . '.max' => 'Telepon gudang maksimal 30 karakter.',
        ];
    }
}
