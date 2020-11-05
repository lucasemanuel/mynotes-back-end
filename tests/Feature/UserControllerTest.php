<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }
}
