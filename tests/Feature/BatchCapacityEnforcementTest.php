<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sport;
use App\Models\Level;
use App\Models\Batch;
use App\Imports\PlayersImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class BatchCapacityEnforcementTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $sport;
    protected $level;
    protected $batch;
    protected $playerRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->playerRole = Role::firstOrCreate(['name' => 'player']);
        
        $permissions = [
            'batch_create', 'batch_edit', 'batch_view',
            'player_create', 'player_edit', 'player_view'
        ];
        foreach ($permissions as $pName) {
            $perm = Permission::firstOrCreate(['name' => $pName], ['module_name' => 'SAMS']);
            $adminRole->givePermissionTo($perm);
        }

        // Create admin user
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

        // Create Sport, Level, Batch with capacity 1
        $this->sport = Sport::create(['name' => 'Tennis', 'slug' => 'tennis', 'status' => 'active']);
        $this->level = Level::create(['name' => 'Beginner', 'slug' => 'beginner', 'status' => 'active']);
        $this->batch = Batch::create([
            'name' => 'Morning Batch',
            'capacity' => 1,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'sport_id' => $this->sport->id,
            'level_id' => $this->level->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test creating a player fails when batch capacity is reached.
     */
    public function test_player_creation_fails_when_batch_is_at_capacity()
    {
        $this->actingAs($this->adminUser);

        // Assign one player to fill the batch
        $player1 = User::create([
            'firstname' => 'Player',
            'lastname' => 'One',
            'email' => 'player1@example.com',
            'phone' => '0000000001',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now(),
            'role' => 'player',
        ]);
        $this->batch->players()->attach($player1->id, ['joined_at' => now()]);

        // Attempt to create player 2 and assign to the same batch
        $response = $this->postJson(route('players.store'), [
            'firstname' => 'Player',
            'lastname' => 'Two',
            'email' => 'player2@example.com',
            'phone' => '0000000002',
            'joined_at' => now()->toDateString(),
            'gender' => 'male',
            'assignments' => [
                [
                    'sport_id' => $this->sport->id,
                    'level_id' => $this->level->id,
                    'batch_id' => $this->batch->id,
                    'joined_at' => now()->toDateString(),
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => "Batch '{$this->batch->name}' has reached its maximum capacity of 1 players.",
        ]);
    }

    /**
     * Test updating a player fails when batch capacity is reached.
     */
    public function test_player_update_fails_when_batch_is_at_capacity()
    {
        $this->actingAs($this->adminUser);

        // Assign one player to fill the batch
        $player1 = User::create([
            'firstname' => 'Player',
            'lastname' => 'One',
            'email' => 'player1@example.com',
            'phone' => '0000000001',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now(),
            'role' => 'player',
        ]);
        $this->batch->players()->attach($player1->id, ['joined_at' => now()]);

        // Player 2 is currently not assigned to any batch
        $player2 = User::create([
            'firstname' => 'Player',
            'lastname' => 'Two',
            'email' => 'player2@example.com',
            'phone' => '0000000002',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now(),
            'role' => 'player',
        ]);

        // Attempt to update player 2 and assign to the full batch
        $response = $this->putJson(route('players.update', $player2->id), [
            'firstname' => 'Player',
            'lastname' => 'Two Updated',
            'email' => 'player2@example.com',
            'phone' => '0000000002',
            'joined_at' => now()->toDateString(),
            'gender' => 'male',
            'status' => 'active',
            'assignments' => [
                [
                    'batch_id' => $this->batch->id,
                    'joined_at' => now()->toDateString(),
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => "Batch '{$this->batch->name}' has reached its maximum capacity of 1 players.",
        ]);
    }

    /**
     * Test batch controller update validation checks player capacity.
     */
    public function test_batch_controller_update_checks_player_capacity()
    {
        $this->actingAs($this->adminUser);

        // Create player 1 and 2
        $player1 = User::create([
            'firstname' => 'Player',
            'lastname' => 'One',
            'phone' => '0000000001',
            'status' => 'active',
            'role' => 'player',
        ]);
        $player2 = User::create([
            'firstname' => 'Player',
            'lastname' => 'Two',
            'phone' => '0000000002',
            'status' => 'active',
            'role' => 'player',
        ]);

        // Attempt to sync 2 players to a batch of capacity 1
        $response = $this->putJson(route('batches.update', $this->batch->id), [
            'name' => 'Morning Batch',
            'capacity' => 1,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'sport_id' => $this->sport->id,
            'level_id' => $this->level->id,
            'players' => [$player1->id, $player2->id],
            'status' => 'active',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Players limit exceeded.',
        ]);
    }

    /**
     * Test import skips record when batch is full.
     */
    public function test_import_skips_row_when_batch_is_full()
    {
        // Fill batch
        $player1 = User::create([
            'firstname' => 'Player',
            'lastname' => 'One',
            'phone' => '0000000001',
            'status' => 'active',
            'role' => 'player',
        ]);
        $this->batch->players()->attach($player1->id, ['joined_at' => now()]);

        $row = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '9999999999',
            'email' => 'john@example.com',
            'joined_at' => '2026-06-30',
            'gender' => 'male',
            'status' => 'active',
            'sport' => 'Tennis',
            'level' => 'Beginner',
            'batch' => 'Morning Batch',
        ];

        $import = new PlayersImport();
        $import->model($row);

        $this->assertEquals(0, $import->getImportedCount());
        $this->assertEquals(1, $import->getSkippedCount());
        $this->assertStringContainsString("has reached its maximum capacity", $import->getErrors()[0]);
    }
}
