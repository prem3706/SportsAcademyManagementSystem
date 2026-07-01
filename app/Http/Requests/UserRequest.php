<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'phone' => 'required|string|max:10|unique:users,phone',
                'password' => 'required|string|min:8',
                'role' => 'required|exists:roles,name',
                'gender' => 'required|in:male,female,other',
                'status' => 'required|in:active,inactive',
                'joined_at' => 'nullable|date',
            ];
        }

        // PUT/PATCH rules
        $userId = $this->route('user') ?? $this->user;
        if (is_object($userId)) {
            $userId = $userId->id;
        }

        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:10', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
            'gender' => 'required|in:male,female,other',
            'status' => 'required|in:active,inactive',
            'joined_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'firstname.required' => 'The first name is required.',
            'firstname.string' => 'The first name must be a valid string.',
            'firstname.max' => 'The first name must not exceed 255 characters.',
            'lastname.required' => 'The last name is required.',
            'lastname.string' => 'The last name must be a valid string.',
            'lastname.max' => 'The last name must not exceed 255 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'The phone number is required.',
            'phone.max' => 'The phone number must not exceed 10 digits.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'A password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'role.required' => 'Please assign a role to the user.',
            'role.exists' => 'The selected role is invalid.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'The selected gender is invalid.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be active or inactive.',
            'joined_at.date' => 'The joined date must be a valid date.',
        ];
    }
}
