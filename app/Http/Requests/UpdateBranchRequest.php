<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\BranchColumns;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            BranchColumns::NAME => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('branches', 'branch_name')->ignore($this->branch)
            ],
            BranchColumns::ADDRESS => 'required|string|min:3|max:100',
            BranchColumns::PHONE => 'required|string|min:3|max:30',
            BranchColumns::IS_ACTIVE => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            BranchColumns::NAME . '.required' => 'Nama cabang wajib diisi',
            BranchColumns::NAME . '.unique' => 'Nama cabang sudah digunakan',
            BranchColumns::NAME . '.min' => 'Nama cabang minimal 3 karakter',
            BranchColumns::NAME . '.max' => 'Nama cabang maksimal 50 karakter',
            BranchColumns::ADDRESS . '.required' => 'Alamat cabang wajib diisi',
            BranchColumns::ADDRESS . '.min' => 'Alamat cabang minimal 3 karakter',
            BranchColumns::ADDRESS . '.max' => 'Alamat cabang maksimal 100 karakter',
            BranchColumns::PHONE . '.required' => 'Telepon cabang wajib diisi',
            BranchColumns::PHONE . '.min' => 'Telepon cabang minimal 3 karakter',
            BranchColumns::PHONE . '.max' => 'Telepon cabang maksimal 30 karakter',
        ];
    }
}
