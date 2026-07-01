<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strtolower(trim($this->input('name'))),
            ]);
        }
    }

    public function rules(): array
    {
        $roleId = $this->route('role') ?? $this->role;
        if (is_object($roleId)) {
            $roleId = $roleId->id;
        }

        return [
            'name' => 'required|string|max:255|unique:roles,name' . ($roleId ? ',' . $roleId : ''),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required.',
            'name.string' => 'The role name must be a string.',
            'name.max' => 'The role name must not exceed 255 characters.',
            'name.unique' => 'This role name has already been taken.',
        ];
    }
}
