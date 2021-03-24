<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function should_create_a_user()
    {
        $data = [
            'email' => 'any@email.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/users', $data);
        $response->assertCreated();
    }

    /** @test */
    public function should_return_unprocessable_entity_when_incorrect_data_is_sent()
    {
        ($this->postJson('/api/users', [
            'email' => 'no_email',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]))->assertStatus(422);

        ($this->postJson('/api/users', [
            'email' => 'email@email.com',
            'password' => '123456',
        ]))->assertStatus(422);

        ($this->postJson('/api/users', [
            'email' => 'email@email.com',
            'password' => '123456',
            'password_confirmation' => 'abcdef'
        ]))->assertStatus(422);
    }

    /** @test */
    public function should_return_logged_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/users');

        $response->assertOk();
        $response->assertJson($user->toArray());
    }

    /** @test */
    public function should_return_unauthorized_when_the_user_is_not_logged()
    {
        $response = $this->getJson('/api/users');
        $response->assertUnauthorized();
    }
}
