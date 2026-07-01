<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->routeIs('import.export.export')) {
            return [
                'columns' => 'required|array',
            ];
        }

        if ($this->routeIs('import.export.preview')) {
            return [
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            ];
        }

        return [
            'sports' => 'array',
            'levels' => 'array',
            'sport_levels' => 'array',
            'expense_categories' => 'array',
            'batches' => 'array',
            'users' => 'array',
            'expenses' => 'array',
            'players' => 'array',
        ];
    }

    public function messages(): array
    {
        return [
            'columns.required' => 'Please select at least one column to export.',
            'columns.array' => 'The columns parameter must be an array.',
            'file.required' => 'Please upload a file.',
            'file.file' => 'The uploaded file is not valid.',
            'file.mimes' => 'The file must be an Excel sheet or a CSV file (.xlsx, .xls, .csv).',
            'file.max' => 'The file size must not exceed 10MB.',
            'sports.array' => 'The sports data must be an array.',
            'levels.array' => 'The levels data must be an array.',
            'sport_levels.array' => 'The sport levels data must be an array.',
            'expense_categories.array' => 'The expense categories data must be an array.',
            'batches.array' => 'The batches data must be an array.',
            'users.array' => 'The users data must be an array.',
            'expenses.array' => 'The expenses data must be an array.',
            'players.array' => 'The players data must be an array.',
        ];
    }
}
