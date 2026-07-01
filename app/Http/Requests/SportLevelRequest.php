<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SportLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sport_id' => 'required|exists:sports,id',
            'levels' => 'required|array',
            'levels.*.level_id' => 'required|exists:levels,id',
            'levels.*.fees' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'sport_id.required' => 'Please select a sport.',
            'sport_id.exists' => 'The selected sport is invalid.',
            'levels.required' => 'Please add at least one level.',
            'levels.array' => 'Levels data must be an array.',
            'levels.*.level_id.required' => 'Please select a level.',
            'levels.*.level_id.exists' => 'One of the selected levels is invalid.',
            'levels.*.fees.required' => 'Please enter the fees for each level.',
            'levels.*.fees.numeric' => 'Fees must be a valid number.',
            'levels.*.fees.min' => 'Fees cannot be negative.',
        ];
    }
}
