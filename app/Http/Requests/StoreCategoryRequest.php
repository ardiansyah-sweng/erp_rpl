<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CategoryColumns;
use App\Constants\Messages;

class StoreCategoryRequest extends FormRequest
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
        $tableName = config('db_tables.category');
        
        return [
            CategoryColumns::CATEGORY => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:' . $tableName . ',category',
                'regex:/^[a-zA-Z0-9\s\-\_\.]+$/', // Allow alphanumeric, spaces, hyphens, underscores, dots
            ],
            CategoryColumns::PARENT => [
                'nullable',
                'integer',
                'exists:' . $tableName . ',id',
            ],
            CategoryColumns::IS_ACTIVE => [
                'boolean',
            ],
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
            CategoryColumns::CATEGORY => 'category name',
            CategoryColumns::PARENT => 'parent category',
            CategoryColumns::IS_ACTIVE => 'active status',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            CategoryColumns::CATEGORY . '.required' => 'Nama kategori wajib diisi.',
            CategoryColumns::CATEGORY . '.string' => 'Nama kategori harus berupa teks.',
            CategoryColumns::CATEGORY . '.min' => Messages::CATEGORY_NAME_TOO_SHORT,
            CategoryColumns::CATEGORY . '.max' => 'Nama kategori maksimal :max karakter.',
            CategoryColumns::CATEGORY . '.unique' => Messages::CATEGORY_NAME_EXISTS,
            CategoryColumns::CATEGORY . '.regex' => Messages::CATEGORY_NAME_INVALID,
            CategoryColumns::PARENT . '.integer' => 'Parent kategori harus berupa angka yang valid.',
            CategoryColumns::PARENT . '.exists' => Messages::CATEGORY_INVALID_PARENT,
            CategoryColumns::IS_ACTIVE . '.boolean' => 'Status aktif harus berupa true atau false.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from category name
        if ($this->has(CategoryColumns::CATEGORY)) {
            $this->merge([
                CategoryColumns::CATEGORY => trim($this->input(CategoryColumns::CATEGORY))
            ]);
        }

        // Convert checkbox value to boolean
        $this->merge([
            CategoryColumns::IS_ACTIVE => $this->boolean(CategoryColumns::IS_ACTIVE)
        ]);
    }

    /**
     * Get validated data with proper formatting.
     *
     * @return array<string, mixed>
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();
        
        // Ensure parent_id is null if not provided
        if (empty($validated[CategoryColumns::PARENT])) {
            $validated[CategoryColumns::PARENT] = null;
        }
        
        return $validated;
    }
}
