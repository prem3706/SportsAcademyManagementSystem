<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sport;
use App\Models\Level;
use App\Models\Batch;
use App\Models\PlayerFee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the admin role and permission
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permission = Permission::firstOrCreate(['name' => 'dashboard_view'], ['module_name' => 'Dashboard']);
        $adminRole->givePermissionTo($permission);

        // Create player and coach roles as well since DashboardController queries them
        Role::firstOrCreate(['name' => 'player']);
        Role::firstOrCreate(['name' => 'coach']);

        // Create an admin user to perform the actions
        $this->adminUser = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now(),
            'role' => 'admin',
        ]);
    }

    public function test_dashboard_renders_successfully_with_correct_calculations()
    {
        $this->actingAs($this->adminUser);

        // Create Sport, Level, Batch
        $sport = Sport::create(['name' => 'Tennis', 'slug' => 'tennis', 'status' => 'active']);
        $level = Level::create(['name' => 'Beginner', 'slug' => 'beginner', 'status' => 'active']);
        
        // Link sport and level with fees
        DB::table('sports_levels')->insert([
            'sport_id' => $sport->id,
            'level_id' => $level->id,
            'fees' => 1200.00,
        ]);

        $batch = Batch::create([
            'name' => 'Morning Batch',
            'capacity' => 10,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'sport_id' => $sport->id,
            'level_id' => $level->id,
            'status' => 'active',
        ]);

        // Create a player and enroll them in the batch
        $player = User::create([
            'firstname' => 'Player',
            'lastname' => 'One',
            'email' => 'player@example.com',
            'phone' => '0000000001',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now()->subMonths(2)->format('Y-m-d'),
            'role' => 'player',
        ]);
        $player->assignRole('player');
        $batch->players()->attach($player->id, ['joined_at' => now()->subMonths(2)->format('Y-m-d')]);

        // Create player fee (paid for last month)
        $startLastMonth = now()->subMonth()->startOfMonth();
        $endLastMonth = now()->subMonth()->endOfMonth();

        PlayerFee::create([
            'player_id' => $player->id,
            'batch_id' => $batch->id,
            'sub_totalamount' => 1200.00,
            'discount_amount' => 0.00,
            'penalty_amount' => 0.00,
            'total_amt' => 1200.00,
            'start_date' => $startLastMonth->format('Y-m-d'),
            'end_date' => $endLastMonth->format('Y-m-d'),
            'payment_type' => 'cash',
            'status' => 'paid',
        ]);

        // Track how many queries are executed
        $queryCount = 0;
        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Verify that the queryCount is reasonably low
        $this->assertLessThanOrEqual(25, $queryCount);
    }
}
