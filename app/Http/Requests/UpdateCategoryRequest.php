<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CategoryColumns;
use App\Constants\Messages;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('id') ?? $this->route('category');
        
        return [
            CategoryColumns::CATEGORY => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:' . $tableName . ',category,' . $categoryId,
                'regex:/^[a-zA-Z0-9\s\-\_\.]+$/', // Allow alphanumeric, spaces, hyphens, underscores, dots
            ],
            CategoryColumns::PARENT => [
                'nullable',
                'integer',
                'exists:' . $tableName . ',id',
                'not_in:' . $categoryId, // Category cannot be parent of itself
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
            CategoryColumns::PARENT . '.not_in' => Messages::CATEGORY_CIRCULAR_DEPENDENCY,
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

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $categoryId = $this->route('id') ?? $this->route('category');
            $parentId = $this->input(CategoryColumns::PARENT);
            
            // Additional validation: check for circular dependency
            if ($parentId && $this->wouldCreateCircularDependency($categoryId, $parentId)) {
                $validator->errors()->add(CategoryColumns::PARENT, Messages::CATEGORY_CIRCULAR_DEPENDENCY);
            }
        });
    }

    /**
     * Check if setting this parent would create a circular dependency.
     *
     * @param int $categoryId
     * @param int $parentId
     * @return bool
     */
    private function wouldCreateCircularDependency(int $categoryId, int $parentId): bool
    {
        $currentParent = $parentId;
        $visited = [];
        
        // Follow the parent chain up to check for circular reference
        while ($currentParent && !in_array($currentParent, $visited)) {
            if ($currentParent == $categoryId) {
                return true; // Found circular dependency
            }
            
            $visited[] = $currentParent;
            
            // Get the parent of the current parent
            $category = \App\Models\Category::find($currentParent);
            $currentParent = $category ? $category->parent_id : null;
        }
        
        return false;
    }
}
