<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Level;
use App\Models\PlayerFee;
use App\Models\Setting;
use App\Models\Sport;
use App\Models\SportsLevel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
    */
    // 1. Truncate existing data to avoid conflicts
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Expense::truncate();
        ExpenseCategory::truncate();
        PlayerFee::truncate();
        DB::table('batch_player')->truncate();
        DB::table('batch_coach')->truncate();
        Batch::truncate();
        SportsLevel::truncate();
        Level::truncate();
        Sport::truncate();
        User::truncate();
        Setting::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Call separated seeders
        $this->call([
            SystemDataSeeder::class,
            TestDataSeeder::class,
            ]);
            }
            }
