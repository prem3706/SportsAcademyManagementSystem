<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'start_time' => 'required',
            'end_time' => 'required',
            'sport_id' => 'required|exists:sports,id',
            'level_id' => 'required|exists:levels,id',
            'coaches' => 'nullable|array',
            'coaches.*' => 'exists:users,id',
            'players' => 'nullable|array',
            'players.*' => 'exists:users,id',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The batch name is required.',
            'name.string' => 'The batch name must be a valid string.',
            'name.max' => 'The batch name must not exceed 255 characters.',
            'capacity.required' => 'The batch capacity is required.',
            'capacity.integer' => 'The capacity must be a valid number.',
            'capacity.min' => 'The capacity must be at least 1.',
            'start_time.required' => 'The start time is required.',
            'end_time.required' => 'The end time is required.',
            'sport_id.required' => 'Please select a sport.',
            'sport_id.exists' => 'The selected sport is invalid.',
            'level_id.required' => 'Please select a training level.',
            'level_id.exists' => 'The selected level is invalid.',
            'coaches.array' => 'Coaches list must be an array.',
            'coaches.*.exists' => 'One of the selected coaches is invalid.',
            'players.array' => 'Players list must be an array.',
            'players.*.exists' => 'One of the selected players is invalid.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be active or inactive.',
        ];
    }
}
