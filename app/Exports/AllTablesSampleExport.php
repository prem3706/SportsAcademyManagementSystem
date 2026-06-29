<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class AllTablesSampleExport implements FromArray
{
    public function array(): array
    {
        return [
            // Row 1: Table Identifiers above their respective columns
            [
                '[Sports]', '', '', 
                '[Levels]', '', 
                '[Sport Levels]', '', '', 
                '[Expense Categories]', '', '', 
                '[Batches]', '', '', '', '', '', '', 
                '[Users]', '', '', '', '', '', '', '', 
                '[Expenses]', '', '', '', '', '', 
                '[Players]'
            ],
            // Row 2: Column Headers
            [
                // Sports
                'name', 'description', 'status',
                // Levels
                'name', 'status',
                // Sport Levels
                'sport', 'level', 'fees',
                // Expense Categories
                'name', 'description', 'status',
                // Batches
                'name', 'capacity', 'start_time', 'end_time', 'sport', 'level', 'status',
                // Users
                'firstname', 'lastname', 'email', 'phone', 'gender', 'role', 'status', 'joined_at',
                // Expenses
                'category', 'expense_date', 'amount', 'payment_mode', 'reference_no', 'description',
                // Players
                'firstname', 'lastname', 'email', 'phone', 'gender', 'status', 'joined_at', 'sport', 'level', 'batch'
            ],
            // Row 3: Mock Data Row 1
            [
                // Sports
                'Football', 'Football Academy', 'active',
                // Levels
                'Beginner', 'active',
                // Sport Levels
                'Football', 'Beginner', '500',
                // Expense Categories
                'Equipment', 'Sports Eq.', 'active',
                // Batches
                'Football Morning', '20', '06:00:00', '08:00:00', 'Football', 'Beginner', 'active',
                // Users
                'John', 'Coach', 'john.coach@example.com', '9876543210', 'male', 'coach', 'active', '2026-06-01',
                // Expenses
                'Equipment', '2026-06-25', '500', 'Cash', '', 'Purchased footballs',
                // Players
                'Bobby', 'Player', 'bobby@example.com', '1234567890', 'male', 'active', '2026-06-26', 'Football', 'Beginner', 'Football Morning'
            ],
            // Row 4: Mock Data Row 2
            [
                // Sports
                'Cricket', 'Cricket Coaching', 'active',
                // Levels
                'Intermediate', 'active',
                // Sport Levels
                'Cricket', 'Intermediate', '600',
                // Expense Categories
                'Rent', 'Ground Rent', 'active',
                // Batches
                'Cricket Evening', '25', '16:00:00', '18:00:00', 'Cricket', 'Intermediate', 'active',
                // Users
                'Alice', 'Staff', 'alice.staff@example.com', '9876543211', 'female', 'admin', 'active', '2026-06-01',
                // Expenses
                'Rent', '2026-06-26', '1200', 'UPI', 'TXN123456', 'Monthly field rent',
                // Players
                'Charlie', 'Player', 'charlie@example.com', '1234567891', 'male', 'active', '2026-06-26', 'Cricket', 'Intermediate', 'Cricket Evening'
            ]
        ];
    }
}
