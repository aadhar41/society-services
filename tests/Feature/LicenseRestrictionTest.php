<?php

namespace Tests\Feature;

use App\Models\License;
use App\Models\User;
use App\Domain\Society\Models\Society;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LicenseRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed licenses
        License::create(['name' => 'Starter', 'slug' => 'starter', 'max_societies' => 1]);
        License::create(['name' => 'Pro', 'slug' => 'pro', 'max_societies' => 5]);
    }

    public function test_can_access_v2_auth_me()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v2/auth/me');

        $response->assertStatus(200);
    }

    public function test_can_access_v1_societies()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/societies');

        $response->assertStatus(200);
    }

    public function test_starter_license_cannot_create_more_than_one_society()
    {
        $license = License::where('slug', 'starter')->first();
        $user = User::factory()->create(['license_id' => $license->id]);

        // Create first society
        $society = Society::create([
            'name' => 'Society 1',
            'address_line_1' => 'Address 1',
            'city' => 'City',
            'state' => 'State',
            'country' => 'India',
            'pincode' => '123456',
        ]);
        $user->societies()->attach($society->id);

        $this->assertEquals(1, $user->societies()->count());

        // Try to create second society
        Sanctum::actingAs($user);
        // dd(route('v2.societies.store')); 
        $response = $this->postJson(route('v2.societies.store'), [
            'name' => 'Society 2',
            'address_line_1' => 'Address 2',
            'city' => 'City',
            'state' => 'State',
            'country' => 'India',
            'pincode' => '123456',
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment(['message' => 'Society limit exceeded for your current license.']);
    }

    public function test_superadmin_can_bypass_license_limit()
    {
        $license = License::where('slug', 'starter')->first();
        $user = User::factory()->create([
            'license_id' => $license->id,
            'is_superadmin' => true
        ]);

        // Create first society
        $society = Society::create([
            'name' => 'Society 1',
            'address_line_1' => 'Address 1',
            'city' => 'City',
            'state' => 'State',
            'country' => 'India',
            'pincode' => '123456',
        ]);
        $user->societies()->attach($society->id);

        // Try to create second society
        Sanctum::actingAs($user);
        $response = $this->postJson(route('v2.societies.store'), [
            'name' => 'Society 2',
            'address_line_1' => 'Address 2',
            'city' => 'City',
            'state' => 'State',
            'country' => 'India',
            'pincode' => '123456',
        ]);

        $response->assertStatus(201);
        $this->assertEquals(2, Society::count());
    }
}
