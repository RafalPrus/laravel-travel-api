<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginReturnsTokenWithValidCredentials(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access token']);
    }

    public function testLoginReturnsErrorWithInvalidPassword(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }

    public function testLoginReturnsErrorWithInvalidEmail(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('api/v1/login', [
            'email' => 'wrong@email.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }

    public function testLoginReturnsErrorWithInvalidCredentials(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('api/v1/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }
}
