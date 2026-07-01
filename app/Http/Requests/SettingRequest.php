<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Discount update rules
        if ($this->routeIs('settings.updateDiscount')) {
            $rules = [
                'discount_type' => 'required|in:fixed,percentage',
                'discount_monthly' => 'required|numeric|min:0',
                'discount_quarterly' => 'required|numeric|min:0',
                'discount_half_yearly' => 'required|numeric|min:0',
                'discount_yearly' => 'required|numeric|min:0',
            ];

            if ($this->discount_type === 'percentage') {
                $rules['discount_monthly'] .= '|max:100';
                $rules['discount_quarterly'] .= '|max:100';
                $rules['discount_half_yearly'] .= '|max:100';
                $rules['discount_yearly'] .= '|max:100';
            }

            return $rules;
        }

        // Penalty update rules
        $rules = [
            'allow_penalty' => 'nullable',
        ];

        if ($this->has('allow_penalty')) {
            $rules = array_merge($rules, [
                'penalty_days' => 'required|integer|min:0',
                'penalty_type' => 'required|in:fixed,percentage',
                'penalty_amount' => 'required|numeric|min:0',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'penalty_days.required' => 'Penalty grace period days is required.',
            'penalty_days.integer' => 'Grace days must be a whole number.',
            'penalty_days.min' => 'Grace days cannot be negative.',
            'penalty_type.required' => 'Please select a penalty type.',
            'penalty_type.in' => 'The selected penalty type is invalid.',
            'penalty_amount.required' => 'Penalty amount is required.',
            'penalty_amount.numeric' => 'Penalty amount must be a number.',
            'penalty_amount.min' => 'Penalty amount cannot be negative.',
            'discount_type.required' => 'Please select a discount type.',
            'discount_type.in' => 'The selected discount type is invalid.',
            'discount_monthly.required' => 'Monthly discount is required.',
            'discount_monthly.numeric' => 'Monthly discount must be a number.',
            'discount_monthly.min' => 'Monthly discount cannot be negative.',
            'discount_monthly.max' => 'Monthly discount percentage cannot exceed 100%.',
            'discount_quarterly.required' => 'Quarterly discount is required.',
            'discount_quarterly.numeric' => 'Quarterly discount must be a number.',
            'discount_quarterly.min' => 'Quarterly discount cannot be negative.',
            'discount_quarterly.max' => 'Quarterly discount percentage cannot exceed 100%.',
            'discount_half_yearly.required' => 'Half-yearly discount is required.',
            'discount_half_yearly.numeric' => 'Half-yearly discount must be a number.',
            'discount_half_yearly.min' => 'Half-yearly discount cannot be negative.',
            'discount_half_yearly.max' => 'Half-yearly discount percentage cannot exceed 100%.',
            'discount_yearly.required' => 'Yearly discount is required.',
            'discount_yearly.numeric' => 'Yearly discount must be a number.',
            'discount_yearly.min' => 'Yearly discount cannot be negative.',
            'discount_yearly.max' => 'Yearly discount percentage cannot exceed 100%.',
        ];
    }
}
