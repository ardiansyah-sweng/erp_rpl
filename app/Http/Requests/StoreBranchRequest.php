<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\BranchColumns;

class StoreBranchRequest extends FormRequest
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
            BranchColumns::NAME => 'required|string|min:3|max:50|unique:branches,branch_name',
            BranchColumns::ADDRESS => 'required|string|min:3|max:100',
            BranchColumns::PHONE => 'required|string|min:3|max:30'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            BranchColumns::NAME . '.required' => 'Nama cabang wajib diisi.',
            BranchColumns::NAME . '.string' => 'Nama cabang harus berupa teks.',
            BranchColumns::NAME . '.min' => 'Nama cabang minimal 3 karakter.',
            BranchColumns::NAME . '.max' => 'Nama cabang maksimal 50 karakter.',
            BranchColumns::NAME . '.unique' => 'Nama cabang sudah ada, silakan gunakan nama lain.',
            
            BranchColumns::ADDRESS . '.required' => 'Alamat cabang wajib diisi.',
            BranchColumns::ADDRESS . '.string' => 'Alamat cabang harus berupa teks.',
            BranchColumns::ADDRESS . '.min' => 'Alamat cabang minimal 3 karakter.',
            BranchColumns::ADDRESS . '.max' => 'Alamat cabang maksimal 100 karakter.',
            
            BranchColumns::PHONE . '.required' => 'Telepon cabang wajib diisi.',
            BranchColumns::PHONE . '.string' => 'Telepon cabang harus berupa teks.',
            BranchColumns::PHONE . '.min' => 'Telepon cabang minimal 3 karakter.',
            BranchColumns::PHONE . '.max' => 'Telepon cabang maksimal 30 karakter.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            BranchColumns::NAME => 'nama cabang',
            BranchColumns::ADDRESS => 'alamat cabang',
            BranchColumns::PHONE => 'telepon cabang'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            BranchColumns::NAME => trim($this->input(BranchColumns::NAME)),
            BranchColumns::ADDRESS => trim($this->input(BranchColumns::ADDRESS)),
            BranchColumns::PHONE => trim($this->input(BranchColumns::PHONE))
        ]);
    }
}
