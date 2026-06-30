<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sport;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SportsLevelDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $sport;
    protected $level;

    protected function setUp(): void
    {
        parent::setUp();

        // Create role and permission
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permission = Permission::firstOrCreate(['name' => 'sports_level_delete'], ['module_name' => 'Sports Level']);
        $adminRole->givePermissionTo($permission);

        // Create admin user
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

        // Create a sport and associate it with a level
        $this->sport = Sport::create([
            'name' => 'Tennis',
            'slug' => 'tennis',
            'status' => 'active',
        ]);
        $this->level = Level::create([
            'name' => 'Beginner',
            'slug' => 'beginner',
            'status' => 'active',
        ]);

        $this->sport->levels()->attach($this->level->id, ['fees' => 1200]);
    }

    /**
     * Test destroying sports levels detaches the levels but does not delete the sport.
     */
    public function test_destroy_detaches_levels_but_keeps_sport()
    {
        $this->actingAs($this->adminUser);

        // Assert mapping exists initially
        $this->assertDatabaseHas('sports_levels', [
            'sport_id' => $this->sport->id,
            'level_id' => $this->level->id,
        ]);

        $response = $this->deleteJson(route('sport-levels.destroy', $this->sport->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Sports Levels deleted successfully.',
        ]);

        // Assert mapping is detached/deleted from pivot table
        $this->assertDatabaseMissing('sports_levels', [
            'sport_id' => $this->sport->id,
            'level_id' => $this->level->id,
        ]);

        // Assert that the Sport model itself still exists
        $this->assertDatabaseHas('sports', [
            'id' => $this->sport->id,
            'name' => 'Tennis',
        ]);
    }
}
