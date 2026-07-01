<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SportRequest extends FormRequest
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
        $sportId = $this->route('sport') ?? $this->sport;
        if (is_object($sportId)) {
            $sportId = $sportId->id;
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sports,slug' . ($sportId ? ',' . $sportId : ''),
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The sport name is required.',
            'name.string' => 'The sport name must be a valid string.',
            'name.max' => 'The sport name must not exceed 255 characters.',
            'slug.required' => 'The slug is required.',
            'slug.string' => 'The slug must be a valid string.',
            'slug.max' => 'The slug must not exceed 255 characters.',
            'slug.unique' => 'This sport name has already been taken.',
            'description.string' => 'The description must be a string.',
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
