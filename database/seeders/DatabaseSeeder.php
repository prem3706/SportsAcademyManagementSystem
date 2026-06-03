<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->coach()->count(5)->create();

        User::factory()->player()->count(10)->create();

        //     User::factory()->create([
        //         'email' => 'test@example.com',
        //         'password' => bcrypt('password'),
        //         'first_name' => 'Test',
        //         'last_name' => 'User',
        //         'phone' => '123-456-7890',
        //         'gender' => 'Male',
        //     ]);
    }
}
