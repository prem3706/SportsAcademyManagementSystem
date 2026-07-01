<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LevelRequest extends FormRequest
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
        $levelId = $this->route('level') ?? $this->level;
        if (is_object($levelId)) {
            $levelId = $levelId->id;
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:levels,slug' . ($levelId ? ',' . $levelId : ''),
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The level name is required.',
            'name.string' => 'The level name must be a valid string.',
            'name.max' => 'The level name must not exceed 255 characters.',
            'slug.required' => 'The slug is required.',
            'slug.string' => 'The slug must be a valid string.',
            'slug.max' => 'The slug must not exceed 255 characters.',
            'slug.unique' => 'This level name has already been taken.',
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
