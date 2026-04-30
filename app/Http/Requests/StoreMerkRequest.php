<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\MerkColumns;

/**
 * StoreMerkRequest
 * 
 * Handles validation for creating new Merk records
 * following Laravel best practices for form request validation.
 */
class StoreMerkRequest extends FormRequest
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
        return [
            'merk' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'unique:' . config('db_tables.merk') . ',merk',
                'regex:/^[a-zA-Z0-9\s\-_.&()]+$/', // Allow alphanumeric, spaces, and common symbols
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
            'is_active' => $this->boolean('is_active', true), // Default to true if not provided
        ]);
    }
}
