<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'nullable|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'expense_category_id.required' => 'Please select an expense category.',
            'expense_category_id.exists' => 'The selected expense category is invalid.',
            'expense_date.required' => 'The expense date is required.',
            'expense_date.date' => 'The expense date must be a valid date.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'payment_mode.string' => 'The payment mode must be a string.',
            'payment_mode.max' => 'The payment mode must not exceed 255 characters.',
            'reference_no.string' => 'The reference number must be a string.',
            'reference_no.max' => 'The reference number must not exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description must not exceed 1000 characters.',
            'receipt.file' => 'The receipt must be a valid file.',
            'receipt.mimes' => 'The receipt must be a file of type: jpeg, png, jpg, pdf.',
            'receipt.max' => 'The receipt size must not exceed 4MB.',
        ];
    }
}
