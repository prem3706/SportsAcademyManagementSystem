<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        // Log::info($this->user);
        $userId = $this->user->id;

        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:10', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,player,coach,manager',
            'gender' => 'required|in:male,female,other',
            'status' => 'required|in:active,inactive',
            'joined_at' => 'nullable|date',
        ];
    }
}
