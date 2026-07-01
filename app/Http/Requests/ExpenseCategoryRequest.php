<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ExpenseCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'slug' => Str::slug($this->input('name')),
            ]);
        }
    }

    public function rules(): array
    {
        $categoryId = $this->route('expense_category') ?? $this->expense_category;
        if (is_object($categoryId)) {
            $categoryId = $categoryId->id;
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:expense_categories,slug' . ($categoryId ? ',' . $categoryId : ''),
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:1,0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.string' => 'The category name must be a valid string.',
            'name.max' => 'The category name must not exceed 255 characters.',
            'slug.required' => 'The slug is required.',
            'slug.string' => 'The slug must be a valid string.',
            'slug.max' => 'The slug must not exceed 255 characters.',
            'slug.unique' => 'This category name has already been taken.',
            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description must not exceed 1000 characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be active or inactive.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('slug')) {
                $validator->errors()->add('name', $validator->errors()->first('slug'));
            }
        });
    }
}
