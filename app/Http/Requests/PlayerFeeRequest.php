<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->routeIs('player-fees.check-overlap')) {
            return [
                'player_id' => 'required|exists:users,id',
                'batch_id' => 'required|exists:batches,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'exclude_id' => 'nullable|integer',
            ];
        }

        $rules = [
            'player_id' => 'required|exists:users,id',
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sub_totalamount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'penalty_amount' => 'required|numeric|min:0',
            'total_amt' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upi,cash,card',
            'status' => 'required|in:paid,pending',
        ];

        if ($this->payment_type === 'upi') {
            $rules['upi_id'] = 'required|string|max:255';
            if ($this->isMethod('post')) {
                $rules['img_upi'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            } else {
                $rules['img_upi'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'player_id.required' => 'Please select a player.',
            'player_id.exists' => 'The selected player is invalid.',
            'batch_id.required' => 'Please select a batch.',
            'batch_id.exists' => 'The selected batch is invalid.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'sub_totalamount.required' => 'The subtotal amount is required.',
            'sub_totalamount.numeric' => 'The subtotal amount must be a number.',
            'sub_totalamount.min' => 'The subtotal amount cannot be negative.',
            'discount_amount.required' => 'The discount amount is required.',
            'discount_amount.numeric' => 'The discount amount must be a number.',
            'discount_amount.min' => 'The discount amount cannot be negative.',
            'penalty_amount.required' => 'The penalty amount is required.',
            'penalty_amount.numeric' => 'The penalty amount must be a number.',
            'penalty_amount.min' => 'The penalty amount cannot be negative.',
            'total_amt.required' => 'The total amount is required.',
            'total_amt.numeric' => 'The total amount must be a number.',
            'total_amt.min' => 'The total amount cannot be negative.',
            'payment_type.required' => 'Please select a payment method.',
            'payment_type.in' => 'The selected payment method is invalid.',
            'status.required' => 'Please select a payment status.',
            'status.in' => 'The selected payment status is invalid.',
            'upi_id.required' => 'UPI transaction reference is required for UPI payment.',
            'upi_id.string' => 'The UPI ID must be a valid string.',
            'upi_id.max' => 'The UPI ID must not exceed 255 characters.',
            'img_upi.required' => 'Transaction screenshot is required for UPI payment.',
            'img_upi.image' => 'The uploaded file must be an image.',
            'img_upi.mimes' => 'The screenshot must be in jpeg, png, or jpg format.',
            'img_upi.max' => 'The screenshot size must not exceed 2MB.',
            'exclude_id.integer' => 'The excluded record ID must be an integer.',
        ];
    }
}
