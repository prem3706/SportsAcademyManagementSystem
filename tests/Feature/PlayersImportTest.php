<?php

namespace Tests\Feature;

use App\Imports\PlayersImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PlayersImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create player role needed for role assignment
        Role::firstOrCreate(['name' => 'player']);
        Role::firstOrCreate(['name' => 'admin']);
    }

    /**
     * Test PlayersImport model method directly for importing a valid player.
     */
    public function test_import_class_persists_valid_player_record()
    {
        $importer = new PlayersImport();
        
        $row = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '9876543210',
            'gender' => 'female',
            'joined_at' => '2026-06-23',
        ];

        $importer->model($row);

        $this->assertDatabaseHas('users', [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '9876543210',
            'gender' => 'female',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);

        $user = User::where('phone', '9876543210')->first();
        $this->assertTrue($user->hasRole('player'));
        // Verify password is generated as: lowercase firstname + @123
        $this->assertTrue(Hash::check('jane@123', $user->password));
    }

    /**
     * Test PlayersImport model method directly for skipping duplicates.
     */
    public function test_import_class_skips_duplicate_email_or_phone()
    {
        // Pre-create a player
        $existing = User::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '9876543210',
            'password' => 'secret123',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $existing->assignRole('player');

        $importer = new PlayersImport();

        // Row with duplicate email but different phone
        $row1 = [
            'first_name' => 'Duplicate',
            'last_name' => 'Email',
            'email' => 'jane.smith@example.com',
            'phone' => '1111111111',
            'gender' => 'male',
            'joined_at' => '2026-06-23',
        ];

        // Row with duplicate phone but different email
        $row2 = [
            'first_name' => 'Duplicate',
            'last_name' => 'Phone',
            'email' => 'other.email@example.com',
            'phone' => '9876543210',
            'gender' => 'male',
            'joined_at' => '2026-06-23',
        ];

        $importer->model($row1);
        $importer->model($row2);

        // Verify no user named "Duplicate" was created
        $this->assertDatabaseMissing('users', [
            'firstname' => 'Duplicate',
        ]);
    }

    /**
     * Test import form route.
     */
    public function test_unauthenticated_user_cannot_access_import_form_route()
    {
        $response = $this->get('/players/import-form');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_permission_cannot_access_import_form_route()
    {
        $player = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $player->assignRole('player');

        $response = $this->actingAs($player)->get('/players/import-form');
        $response->assertStatus(403);
    }

    public function test_authenticated_user_with_permission_can_access_import_form()
    {
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/players/import-form');
        $response->assertStatus(200);
        $response->assertViewIs('players.importForm');
    }

    /**
     * Test importing via file upload route.
     */
    public function test_unauthenticated_user_cannot_access_import_route()
    {
        $response = $this->post('/players/import');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_permission_cannot_access_import_route()
    {
        $player = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'role' => 'player',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $player->assignRole('player');

        $response = $this->actingAs($player)->post('/players/import');
        $response->assertStatus(403);
    }

    public function test_authenticated_user_with_permission_can_import_players()
    {
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $admin->assignRole('admin');

        $playersData = [
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '9876543210',
                'gender' => 'female',
                'joined_at' => '2026-06-23',
            ]
        ];

        $response = $this->actingAs($admin)->postJson('/players/import', [
            'players' => $playersData,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '9876543210',
            'gender' => 'female',
            'joined_at' => '2026-06-23',
        ]);
    }

    public function test_import_route_validates_uploaded_file()
    {
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $admin->assignRole('admin');

        // Post without file to readExcel
        $response = $this->actingAs($admin)->post('/players/readExcel');
        $response->assertStatus(302)
                 ->assertSessionHasErrors('file');

        // Post with invalid file format (e.g. txt)
        $txtFile = UploadedFile::fake()->create('players.txt', 10);
        $response2 = $this->actingAs($admin)->post('/players/readExcel', [
            'file' => $txtFile,
        ]);
        $response2->assertSessionHasErrors('file');
    }

    public function test_authenticated_user_with_permission_can_read_excel()
    {
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'phone' => '0987654321',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => '2026-06-23',
        ]);
        $admin->assignRole('admin');

        Excel::fake();
        Excel::shouldReceive('toArray')
            ->once()
            ->andReturn([
                [
                    ['First Name', 'Last Name', 'Email', 'Phone', 'Gender', 'Joined At'],
                    ['Jane', 'Smith', 'jane.smith@example.com', '9876543210', 'female', '2026-06-23']
                ]
            ]);

        $file = UploadedFile::fake()->create('players.xlsx', 100);

        $response = $this->actingAs($admin)->postJson('/players/readExcel', [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'headers' => ['First Name', 'Last Name', 'Email', 'Phone', 'Gender', 'Joined At'],
            'rows' => [
                ['Jane', 'Smith', 'jane.smith@example.com', '9876543210', 'female', '2026-06-23']
            ]
        ]);
    }
}
