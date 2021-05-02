<?php

namespace Tests\Feature;

use App\RecoveryPassword;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function should_return_token_at_login()
    {
        $user = factory(User::class)->create([
            'password' => 'password'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function should_return_unauthorized_when_password_or_email_are_wrong()
    {
        $user = factory(User::class)->create([
            'password' => 'password'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'PASSWORD'
        ]);

        $response->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function should_return_unauthorized_when_requesting_resources_after_logout()
    {
        $user = factory(User::class)->create([
            'password' => '123456'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertJsonStructure(['token']);

        $this->withHeader('Authorization', "Bearer {$response['token']}")
            ->json('post',  '/api/auth/logout');

        $response = $this->withHeader('Authorization', "Bearer {$response['token']}")
            ->json('post',  '/api/auth/logout');

        $response->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function should_return_refresh_token()
    {
        $user = factory(User::class)->create([
            'password' => 'password'
        ]);

        $token = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ])['token'];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->json('post', '/api/auth/refresh');

        $response->assertOk();
        $response->assertJsonStructure(['token']);
        $this->assertNotEquals($token, $response['token']);
    }

    /** @test */
    public function should_return_ok_if_user_is_logged()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/notes');

        $response->assertOk();
    }

    /** @test */
    public function should_generate_token_to_reset_the_password()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson('api/auth/recovery', [
            'email' => $user->email
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('recovery_passwords', [
            'user_id' => $user->id
        ]);
    }
}
