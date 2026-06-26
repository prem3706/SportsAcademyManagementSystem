<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlayerSampleExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'John',
                'Doe',
                'john@example.com',
                '1234567890',
                'male',
                '2026-06-23',
                'Football',
                'Beginner',
                'Football Junior morning',
                'active'
            ],
            [
                'Jane',
                'Smith',
                'jane@example.com',
                '0987654321',
                'female',
                '2026-06-24',
                'Cricket',
                'Intermediate',
                'Cricket Senior evening',
                'active'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Gender',
            'Joined At',
            'Sport',
            'Level',
            'Batch',
            'Status'
        ];
    }
}
