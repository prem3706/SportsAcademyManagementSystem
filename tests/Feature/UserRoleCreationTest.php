<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserRoleCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the admin role and permission
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permission = Permission::firstOrCreate(['name' => 'user_create'], ['module_name' => 'User']);
        $adminRole->givePermissionTo($permission);
        $playerPermission = Permission::firstOrCreate(['name' => 'player_create'], ['module_name' => 'Player']);
        $adminRole->givePermissionTo($playerPermission);

        // Create an admin user to perform the actions
        $this->adminUser = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now(),
        ]);
        $this->adminUser->assignRole('admin');
    }

    /**
     * Test UserController store fails validation when role does not exist.
     */
    public function test_user_creation_fails_validation_when_role_does_not_exist()
    {
        $this->actingAs($this->adminUser);

        // Make sure a role named 'manager' does NOT exist in the database
        $this->assertDatabaseMissing('roles', ['name' => 'manager']);

        $response = $this->postJson(route('users.store'), [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'phone' => '9876543211',
            'password' => 'password123',
            'role' => 'manager', // Non-existent role
            'gender' => 'male',
            'status' => 'active',
            'joined_at' => '2026-06-30',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['role']);

        // Assert user was not created
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test PlayerController store fails when player role does not exist.
     */
    public function test_player_creation_fails_validation_when_player_role_does_not_exist()
    {
        $this->actingAs($this->adminUser);

        // Create sport, level, and batch
        $sport = \App\Models\Sport::create([
            'name' => 'Football',
            'slug' => 'football',
            'status' => 'active',
        ]);
        $level = \App\Models\Level::create([
            'name' => 'Beginner',
            'slug' => 'beginner',
            'status' => 'active',
        ]);
        $batch = \App\Models\Batch::create([
            'name' => 'Morning Batch',
            'capacity' => 10,
            'start_time' => '06:00:00',
            'end_time' => '08:00:00',
            'sport_id' => $sport->id,
            'level_id' => $level->id,
            'status' => 'active',
        ]);

        // Make sure 'player' role does NOT exist
        $this->assertDatabaseMissing('roles', ['name' => 'player']);

        $response = $this->postJson(route('players.store'), [
            'firstname' => 'Test',
            'lastname' => 'Player',
            'email' => 'player@example.com',
            'phone' => '9876543212',
            'gender' => 'male',
            'joined_at' => '2026-06-30',
            'assignments' => [
                [
                    'sport_id' => $sport->id,
                    'level_id' => $level->id,
                    'batch_id' => $batch->id,
                    'joined_at' => '2026-06-30',
                ]
            ]
        ]);

        // Should return 422 because the player role does not exist
        $response->assertStatus(422);

        // Assert user was not created
        $this->assertDatabaseMissing('users', [
            'email' => 'player@example.com',
        ]);
    }
}
