<?php

namespace Tests\Feature;

use App\Exports\PlayersExport;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PlayersExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create player role needed for User::role('player')
        Role::firstOrCreate(['name' => 'player']);
    }

    public function test_players_export_returns_correct_headings()
    {
        $export = new PlayersExport(['name', 'email', 'phone', 'sport', 'batch']);

        $this->assertEquals([
            'Name',
            'Email',
            'Phone',
            'Sport',
            'Batch',
        ], $export->headings());
    }

    public function test_players_export_returns_player_data_with_sport_and_batch()
    {
        // 1. Create a sport
        $sport = Sport::create([
            'name' => 'Cricket',
            'status' => 'active',
        ]);

        // 2. Create a level
        $level = Level::create([
            'name' => 'Beginner',
            'status' => 'active',
        ]);

        // 3. Create a batch
        $batch = Batch::create([
            'name' => 'Morning Batch',
            'capacity' => 20,
            'start_time' => '06:00:00',
            'end_time' => '08:00:00',
            'sport_id' => $sport->id,
            'level_id' => $level->id,
            'status' => 'active',
        ]);

        // 4. Create a player user
        $player = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'gender' => 'male',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => now()->toDateString(),
        ]);
        $player->assignRole('player');

        // 5. Assign player to batch
        $batch->players()->attach($player->id, [
            'joined_at' => now()->toDateString(),
        ]);

        // Run export
        $export = new PlayersExport(['name', 'email', 'phone', 'sport', 'batch']);
        $collection = $export->collection();

        $this->assertCount(1, $collection);

        $row = $collection->first();
        
        $this->assertEquals('John Doe', $row['Name']);
        $this->assertEquals('john.doe@example.com', $row['Email']);
        $this->assertEquals('1234567890', $row['Phone']);
        $this->assertEquals('Cricket', $row['Sport']);
        $this->assertEquals('Morning Batch', $row['Batch']);
    }

    public function test_players_export_only_exports_players()
    {
        // Create an admin role and user
        Role::firstOrCreate(['name' => 'admin']);
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'gender' => 'male',
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => now()->toDateString(),
        ]);
        $admin->assignRole('admin');

        // Create player
        $player = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'gender' => 'male',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => now()->toDateString(),
        ]);
        $player->assignRole('player');

        // Run export
        $export = new PlayersExport(['name']);
        $collection = $export->collection();

        // Should only export the player, not the admin
        $this->assertCount(1, $collection);
        $this->assertEquals('John Doe', $collection->first()['Name']);
    }

    public function test_unauthenticated_user_cannot_access_export_route()
    {
        $response = $this->post('/players/export');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_permission_cannot_access_export_route()
    {
        Role::firstOrCreate(['name' => 'player']);
        $player = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'gender' => 'male',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => now()->toDateString(),
        ]);
        $player->assignRole('player');
        // Player has no player_view permission by default

        $response = $this->actingAs($player)->post('/players/export');
        $response->assertStatus(403);
    }

    public function test_authenticated_user_with_permission_can_export()
    {
        Role::firstOrCreate(['name' => 'admin']);
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'gender' => 'male',
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => now()->toDateString(),
        ]);
        $admin->assignRole('admin');

        // Admin has all permissions globally via Gate::before in AppServiceProvider
 
        $response = $this->actingAs($admin)->post('/players/export', [
            'columns' => ['name', 'email', 'phone', 'sport', 'batch']
        ]);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('attachment; filename=Players_', $response->headers->get('content-disposition'));
        $this->assertStringEndsWith('.xlsx', $response->headers->get('content-disposition'));
    }
}
