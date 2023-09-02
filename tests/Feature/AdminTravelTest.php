<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTravelTest extends TestCase
{
    use RefreshDatabase;
    public function testPublicUserCannotAccessAddingTravel(): void
    {
        $response = $this->postJson('/api/v1/admin/travels');

        $response->assertStatus(401);
    }

    public function testNonAdminUserCannotAccessAddingTravel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->first()->id);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels');

        $response->assertStatus(403);
    }

    public function testAdminUserWithValidDataCanAddTravel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->first()->id);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'New Travel',
            'is_public' => 1,
            'description' => 'Description of travel',
            'number_of_days' => 5
        ]);

        $response->assertStatus(201);
    }

    public function testAdminUserWithInvalidTravelDataCannotAddTravel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->first()->id);


        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'New Travel',
            'description' => 'Description of travel',
            'number_of_days' => 5
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'New Travel',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'New Travel',
            'is_public' => 'incorrect_value',
            'description' => 'Description of travel',
            'number_of_days' => 5
        ]);

        $response->assertStatus(422);
    }
}
