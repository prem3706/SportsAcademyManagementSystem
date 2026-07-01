<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // import action
        if ($this->routeIs('players.import')) {
            return [
                'players' => 'required|array',
            ];
        }

        // readExcel action
        if ($this->routeIs('players.readExcel')) {
            return [
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            ];
        }

        // store action
        if ($this->isMethod('post')) {
            return [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'phone' => 'required|string|max:10|unique:users,phone',
                'joined_at' => 'required|date',
                'gender' => 'nullable|in:male,female,other',
                'assignments' => 'required|array|min:1',
                'assignments.*.sport_id' => 'required|exists:sports,id',
                'assignments.*.level_id' => 'required|exists:levels,id',
                'assignments.*.batch_id' => 'required|exists:batches,id',
                'assignments.*.joined_at' => 'required|date',
            ];
        }

        // update action
        $playerId = $this->route('player') ?? $this->player;
        if (is_object($playerId)) {
            $playerId = $playerId->id;
        }

        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $playerId,
            'phone' => 'required|string|max:10|unique:users,phone,' . $playerId,
            'joined_at' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'required|in:active,inactive',
            'assignments' => 'required|array|min:1',
            'assignments.*.batch_id' => 'required|exists:batches,id',
            'assignments.*.joined_at' => 'required|date',
        ];
    }

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
            'joined_at.required' => 'The joined date is required.',
            'joined_at.date' => 'The joined date must be a valid date.',
            'gender.in' => 'The selected gender is invalid.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be active or inactive.',
            'assignments.required' => 'Please add at least one sport/batch assignment.',
            'assignments.array' => 'Assignments must be an array.',
            'assignments.min' => 'Please add at least one sport/batch assignment.',
            'assignments.*.sport_id.required' => 'Please select a sport for each assignment.',
            'assignments.*.sport_id.exists' => 'The selected sport is invalid.',
            'assignments.*.level_id.required' => 'Please select a level for each assignment.',
            'assignments.*.level_id.exists' => 'The selected level is invalid.',
            'assignments.*.batch_id.required' => 'Please select a batch for each assignment.',
            'assignments.*.batch_id.exists' => 'The selected batch is invalid.',
            'assignments.*.joined_at.required' => 'Please select a joined date for each assignment.',
            'assignments.*.joined_at.date' => 'The assignment joined date must be a valid date.',
            'players.required' => 'The players list is required for import.',
            'players.array' => 'The players data must be a valid array.',
            'file.required' => 'Please upload a file.',
            'file.file' => 'The uploaded file is not valid.',
            'file.mimes' => 'The file must be an Excel sheet or a CSV file (.xlsx, .xls, .csv).',
            'file.max' => 'The file size must not exceed 10MB.',
        ];
    }
}
