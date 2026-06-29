<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\MerkColumns;

/**
 * UpdateMerkRequest
 * 
 * Handles validation for updating existing Merk records
 * with unique validation that excludes the current record.
 */
class UpdateMerkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $merkId = $this->route('merk') ?? $this->route('id');
        
        return [
            'merk' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique(config('db_tables.merk'), 'merk')->ignore($merkId),
                'regex:/^[a-zA-Z0-9\s\-_.&()]+$/',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'merk.required' => 'Nama merk harus diisi.',
            'merk.string' => 'Nama merk harus berupa teks.',
            'merk.min' => 'Nama merk minimal 2 karakter.',
            'merk.max' => 'Nama merk maksimal 50 karakter.',
            'merk.unique' => 'Nama merk sudah digunakan.',
            'merk.regex' => 'Nama merk hanya boleh mengandung huruf, angka, dan simbol umum.',
            'is_active.boolean' => 'Status aktif harus berupa true atau false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'merk' => 'nama merk',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'merk' => trim($this->merk),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
