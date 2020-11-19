<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function should_create_a_user()
    {
        $email = 'any@email.com';
        $data = [
            'email' => $email,
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/users', $data);
        $response->assertCreated();
    }

    /** @test */
    public function should_return_unprocessable_entity_when_incorrect_data_is_sent()
    {
        $response1 = $this->postJson('/api/users', [
            'email' => 'no_email',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $response1->assertStatus(422);

        $response2 = $this->postJson('/api/users', [
            'email' => 'email@email.com',
            'password' => '123456',
        ]);
        $response2->assertStatus(422);

        $response3 = $this->postJson('/api/users', [
            'email' => 'email@email.com',
            'password' => '123456',
            'password_confirmation' => 'abcdef'
        ]);
        $response3->assertStatus(422);
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
