<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Level;
use App\Models\PlayerFee;
use App\Models\Sport;
use App\Models\SportsLevel;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Manager
        $manager = User::create([
            'firstname' => 'Academy',
            'lastname' => 'Manager',
            'email' => 'manager@sams.com',
            'password' => 'manager123',
            'phone' => '9876543211',
            'gender' => 'male',
            'status' => 'active',
            'joined_at' => now(),
            'role' => 'manager',
        ]);

        // 2. Seed Expense Categories
        $expenseCategoriesData = [
            ['name' => 'Rent', 'slug' => 'rent', 'description' => 'Monthly facility rent', 'status' => true],
            ['name' => 'Equipment', 'slug' => 'equipment', 'description' => 'Sports equipment like balls, bats, nets, etc.', 'status' => true],
            ['name' => 'Utilities', 'slug' => 'utilities', 'description' => 'Electricity, water, and internet bills', 'status' => true],
            ['name' => 'Salaries', 'slug' => 'salaries', 'description' => 'Coaches and staff monthly salaries', 'status' => true],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Promotional events and advertisements', 'status' => true],
        ];

        $expenseCategories = [];
        foreach ($expenseCategoriesData as $cat) {
            $expenseCategories[$cat['name']] = ExpenseCategory::create($cat);
        }

        // 3. Seed Expenses
        $expensesData = [
            [
                'category' => 'Rent',
                'amount' => 5000.00,
                'payment_mode' => 'card',
                'reference_no' => 'TXN998877',
                'description' => 'Monthly rent for the sports ground',
                'expense_date' => now()->startOfMonth()->format('Y-m-d'),
            ],
            [
                'category' => 'Rent',
                'amount' => 5000.00,
                'payment_mode' => 'card',
                'reference_no' => 'TXN998855',
                'description' => 'Monthly rent for the sports ground',
                'expense_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'),
            ],
            [
                'category' => 'Rent',
                'amount' => 5000.00,
                'payment_mode' => 'card',
                'reference_no' => 'TXN998833',
                'description' => 'Monthly rent for the sports ground',
                'expense_date' => now()->subMonths(2)->startOfMonth()->format('Y-m-d'),
            ],
            [
                'category' => 'Utilities',
                'amount' => 450.00,
                'payment_mode' => 'upi',
                'reference_no' => 'UPI88776611',
                'description' => 'Electricity Bill',
                'expense_date' => now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'category' => 'Utilities',
                'amount' => 520.00,
                'payment_mode' => 'upi',
                'reference_no' => 'UPI88776600',
                'description' => 'Electricity Bill',
                'expense_date' => now()->subMonth()->subDays(5)->format('Y-m-d'),
            ],
            [
                'category' => 'Utilities',
                'amount' => 120.00,
                'payment_mode' => 'cash',
                'reference_no' => null,
                'description' => 'Water Bill',
                'expense_date' => now()->subDays(10)->format('Y-m-d'),
            ],
            [
                'category' => 'Equipment',
                'amount' => 1200.00,
                'payment_mode' => 'upi',
                'reference_no' => 'UPI55443322',
                'description' => 'Purchased footballs and cones',
                'expense_date' => now()->subWeeks(2)->format('Y-m-d'),
            ],
            [
                'category' => 'Equipment',
                'amount' => 2500.00,
                'payment_mode' => 'card',
                'reference_no' => 'TXN332211',
                'description' => 'Purchased cricket training gear',
                'expense_date' => now()->subMonth()->subWeeks(1)->format('Y-m-d'),
            ],
            [
                'category' => 'Salaries',
                'amount' => 3000.00,
                'payment_mode' => 'upi',
                'reference_no' => 'UPI11223344',
                'description' => 'Monthly coach salary - Part-time staff',
                'expense_date' => now()->subMonth()->endOfMonth()->format('Y-m-d'),
            ],
            [
                'category' => 'Marketing',
                'amount' => 600.00,
                'payment_mode' => 'upi',
                'reference_no' => 'UPI44556677',
                'description' => 'Facebook and Instagram local ads',
                'expense_date' => now()->subDays(12)->format('Y-m-d'),
            ],
        ];

        foreach ($expensesData as $exp) {
            Expense::create([
                'expense_category_id' => $expenseCategories[$exp['category']]->id,
                'amount' => $exp['amount'],
                'payment_mode' => $exp['payment_mode'],
                'reference_no' => $exp['reference_no'],
                'description' => $exp['description'],
                'expense_date' => $exp['expense_date'],
                'created_by' => $manager->id,
            ]);
        }

        // 4. Seed Sports
        $sportsData = [
            ['name' => 'Football', 'slug' => 'football', 'description' => 'Association Football', 'status' => 'active'],
            ['name' => 'Cricket', 'slug' => 'cricket', 'description' => 'Bat-and-ball game', 'status' => 'active'],
            ['name' => 'Badminton', 'slug' => 'badminton', 'description' => 'Racket sport', 'status' => 'active'],
            ['name' => 'Basketball', 'slug' => 'basketball', 'description' => 'Team sport played on court', 'status' => 'active'],
        ];

        $sports = [];
        foreach ($sportsData as $data) {
            $sports[$data['name']] = Sport::create($data);
        }

        // 5. Seed Levels
        $levelsData = [
            ['name' => 'Beginner', 'slug' => 'beginner', 'status' => 'active'],
            ['name' => 'Intermediate', 'slug' => 'intermediate', 'status' => 'active'],
            ['name' => 'Advanced', 'slug' => 'advanced', 'status' => 'active'],
        ];

        $levels = [];
        foreach ($levelsData as $data) {
            $levels[$data['name']] = Level::create($data);
        }

        // 6. Seed Sports Levels with Fees
        $feesData = [
            'Football' => [
                'Beginner' => 1500.00,
                'Intermediate' => 2000.00,
                'Advanced' => 2500.00,
            ],
            'Cricket' => [
                'Beginner' => 2000.00,
                'Intermediate' => 3000.00,
                'Advanced' => 4500.00,
            ],
            'Badminton' => [
                'Beginner' => 1800.00,
                'Intermediate' => 2500.00,
                'Advanced' => 3500.00,
            ],
            'Basketball' => [
                'Beginner' => 1600.00,
                'Intermediate' => 2200.00,
                'Advanced' => 3000.00,
            ],
        ];

        foreach ($feesData as $sportName => $levelFees) {
            $sport = $sports[$sportName];
            foreach ($levelFees as $levelName => $fee) {
                $level = $levels[$levelName];
                SportsLevel::create([
                    'sport_id' => $sport->id,
                    'level_id' => $level->id,
                    'fees' => $fee,
                ]);
            }
        }

        // 7. Seed Coaches & Players
        $coaches = User::factory()->coach()->count(5)->create();
        $players = User::factory()->player()->count(15)->create();

        // 8. Seed Batches
        $batchesData = [
            [
                'name' => 'Football Junior morning',
                'capacity' => 15,
                'start_time' => '07:00:00',
                'end_time' => '09:00:00',
                'sport' => 'Football',
                'level' => 'Beginner',
            ],
            [
                'name' => 'Football Elite evening',
                'capacity' => 12,
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'sport' => 'Football',
                'level' => 'Advanced',
            ],
            [
                'name' => 'Cricket Morning Batch',
                'capacity' => 20,
                'start_time' => '06:00:00',
                'end_time' => '09:00:00',
                'sport' => 'Cricket',
                'level' => 'Beginner',
            ],
            [
                'name' => 'Cricket Pro Batch',
                'capacity' => 10,
                'start_time' => '15:30:00',
                'end_time' => '18:30:00',
                'sport' => 'Cricket',
                'level' => 'Advanced',
            ],
            [
                'name' => 'Badminton Intermediate Morning',
                'capacity' => 8,
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'sport' => 'Badminton',
                'level' => 'Intermediate',
            ],
            [
                'name' => 'Basketball Beginner Afternoon',
                'capacity' => 15,
                'start_time' => '16:00:00',
                'end_time' => '18:00:00',
                'sport' => 'Basketball',
                'level' => 'Beginner',
            ],
        ];

        $batches = [];
        foreach ($batchesData as $bData) {
            $sport = $sports[$bData['sport']];
            $level = $levels[$bData['level']];

            $batch = Batch::create([
                'name' => $bData['name'],
                'capacity' => $bData['capacity'],
                'start_time' => $bData['start_time'],
                'end_time' => $bData['end_time'],
                'sport_id' => $sport->id,
                'level_id' => $level->id,
                'status' => 'active',
            ]);

            // Assign a random coach to this batch
            $batch->coaches()->attach($coaches->random()->id);
            $batches[] = $batch;
        }

        // 9. Enroll Players into Batches
        foreach ($players as $player) {
            $playerBatches = collect($batches)->random(rand(1, 2));
            foreach ($playerBatches as $batch) {
                $batch->players()->attach($player->id, ['joined_at' => now()->subMonths(rand(1, 6))]);
            }
        }

        // 10. Seed paid fee records in database
        foreach ($players as $player) {
            $playerEnrolledBatches = $player->playerBatches()->get();
            foreach ($playerEnrolledBatches as $batch) {
                $sportsLevel = SportsLevel::where('sport_id', $batch->sport_id)
                    ->where('level_id', $batch->level_id)
                    ->first();
                $monthlyFee = $sportsLevel ? floatval($sportsLevel->fees) : 1000.00;

                $monthsPaid = rand(1, 2);
                for ($i = 0; $i < $monthsPaid; $i++) {
                    $startMonthDate = now()->subMonths($i + 1)->startOfMonth();
                    $endMonthDate = now()->subMonths($i + 1)->endOfMonth();

                    PlayerFee::create([
                        'player_id' => $player->id,
                        'batch_id' => $batch->id,
                        'sub_totalamount' => $monthlyFee,
                        'discount_amount' => 0.00,
                        'penalty_amount' => 0.00,
                        'total_amt' => $monthlyFee,
                        'start_date' => $startMonthDate->format('Y-m-d'),
                        'end_date' => $endMonthDate->format('Y-m-d'),
                        'payment_type' => fake()->randomElement(['cash', 'card', 'upi']),
                        'status' => 'paid',
                    ]);
                }
            }
        }
    }
}
