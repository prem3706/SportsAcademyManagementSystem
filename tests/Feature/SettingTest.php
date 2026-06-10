<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test settings index returns default settings successfully.
     */
    public function test_settings_index_sets_defaults_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertViewHas('settings');
        
        $settings = Setting::first();
        $this->assertNotNull($settings);
        $this->assertEquals('percentage', $settings->discount_type);
        $this->assertEquals(0.00, $settings->discount_monthly);
    }

    /**
     * Test settings update valid percentage discounts.
     */
    public function test_settings_update_percentage_discounts_validation(): void
    {
        $user = User::factory()->create();

        // Valid percentage discount values (0 to 100)
        $response = $this->actingAs($user)->postJson(route('settings.updateDiscount'), [
            'discount_type' => 'percentage',
            'discount_monthly' => 10,
            'discount_quarterly' => 15,
            'discount_half_yearly' => 20,
            'discount_yearly' => 25,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $settings = Setting::first();
        $this->assertEquals('percentage', $settings->discount_type);
        $this->assertEquals(10.00, $settings->discount_monthly);

        // Invalid percentage discount value (> 100)
        $responseInvalid = $this->actingAs($user)->postJson(route('settings.updateDiscount'), [
            'discount_type' => 'percentage',
            'discount_monthly' => 120, // Invalid
            'discount_quarterly' => 15,
            'discount_half_yearly' => 20,
            'discount_yearly' => 25,
        ]);

        $responseInvalid->assertStatus(422);
        $responseInvalid->assertJsonValidationErrors(['discount_monthly']);
    }

    /**
     * Test settings update valid fixed discounts.
     */
    public function test_settings_update_fixed_discounts_validation(): void
    {
        $user = User::factory()->create();

        // Valid fixed discount values can be larger than 100
        $response = $this->actingAs($user)->postJson(route('settings.updateDiscount'), [
            'discount_type' => 'fixed',
            'discount_monthly' => 150.50,
            'discount_quarterly' => 300,
            'discount_half_yearly' => 500,
            'discount_yearly' => 1000,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $settings = Setting::first();
        $this->assertEquals('fixed', $settings->discount_type);
        $this->assertEquals(150.50, $settings->discount_monthly);
        $this->assertEquals(1000.00, $settings->discount_yearly);
    }
}
