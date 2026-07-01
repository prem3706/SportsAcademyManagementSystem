<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the role
        Role::firstOrCreate(['name' => 'admin']);

        // Create an admin user to perform the actions
        $this->user = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'status' => 'active',
            'joined_at' => now(),
            'gender' => 'male',
            'role' => 'admin',
        ]);
    }

    public function test_profile_page_is_accessible_to_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('My Profile');
        $response->assertSee('John');
        $response->assertSee('Doe');
    }

    public function test_profile_can_be_updated_with_valid_data()
    {
        Storage::fake('public');

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->user)->postJson(route('profile.update'), [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '0987654321',
            'gender' => 'female',
            'profile_picture' => $avatar,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);

        $this->user->refresh();
        $this->assertEquals('Jane', $this->user->firstname);
        $this->assertEquals('Smith', $this->user->lastname);
        $this->assertEquals('jane.smith@example.com', $this->user->email);
        $this->assertEquals('0987654321', $this->user->phone);
        $this->assertEquals('female', $this->user->gender);
        $this->assertNotNull($this->user->profile_picture);
        
        Storage::disk('public')->assertExists($this->user->profile_picture);
    }

    public function test_profile_update_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->user)->postJson(route('profile.update'), [
            'firstname' => '',
            'lastname' => 'Smith',
            'email' => 'not-an-email',
            'phone' => '123456789012345', // exceeds max:10
            'gender' => 'invalid-gender',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['firstname', 'email', 'phone', 'gender']);
    }

    public function test_profile_password_can_be_updated()
    {
        $response = $this->actingAs($this->user)->postJson(route('profile.update'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'gender' => 'male',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $this->user->refresh();
        $this->assertTrue(\Hash::check('newpassword123', $this->user->password));
    }

    public function test_profile_picture_can_be_removed()
    {
        Storage::fake('public');

        // First set a picture
        $this->user->update([
            'profile_picture' => 'profile_pictures/existing.jpg'
        ]);

        Storage::disk('public')->put('profile_pictures/existing.jpg', 'fake-image-content');

        // Send request to remove
        $response = $this->actingAs($this->user)->postJson(route('profile.update'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'gender' => 'male',
            'remove_profile_picture' => '1',
        ]);

        $response->assertStatus(200);
        
        $this->user->refresh();
        $this->assertNull($this->user->profile_picture);
        
        // Assert old picture was deleted from storage
        Storage::disk('public')->assertMissing('profile_pictures/existing.jpg');
    }

    public function test_unauthenticated_user_cannot_access_profile()
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));

        $responseUpdate = $this->post(route('profile.update'), [
            'firstname' => 'Jane',
        ]);
        $responseUpdate->assertRedirect(route('login'));
    }
}
