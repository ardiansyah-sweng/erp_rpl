<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\BranchColumns;
use App\Constants\Messages;

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
            BranchColumns::NAME . '.required' => Messages::BRANCH_NAME_EMPTY,
            BranchColumns::NAME . '.string' => Messages::BRANCH_NAME_NOT_TEXT,
            BranchColumns::NAME . '.min' => Messages::BRANCH_NAME_TOO_SHORT,
            BranchColumns::NAME . '.max' => Messages::BRANCH_NAME_TOO_LONG,
            BranchColumns::NAME . '.unique' => Messages::BRANCH_NAME_EXISTS,
            
            BranchColumns::ADDRESS . '.required' => Messages::BRANCH_ADDRESS_EMPTY,
            BranchColumns::ADDRESS . '.string' => Messages::BRANCH_ADDRESS_NOT_TEXT,
            BranchColumns::ADDRESS . '.min' => Messages::BRANCH_ADDRESS_TOO_SHORT,
            BranchColumns::ADDRESS . '.max' => Messages::BRANCH_ADDRESS_TOO_LONG,
            
            BranchColumns::PHONE . '.required' => Messages::BRANCH_PHONE_EMPTY,
            BranchColumns::PHONE . '.string' => Messages::BRANCH_PHONE_NOT_TEXT,
            BranchColumns::PHONE . '.min' => Messages::BRANCH_PHONE_TOO_SHORT,
            BranchColumns::PHONE . '.max' => Messages::BRANCH_PHONE_TOO_LONG
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
