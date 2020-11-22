<?php

namespace Tests\Feature;

use App\Note;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function should_create_a_note()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/notes', [
                'body' => 'Essa é uma nota legal',
            ]);

        $response->assertCreated();
        $response->assertJsonStructure(['id', 'body', 'is_favorite', 'created_at', 'updated_at']);
        $this->assertDatabaseHas('notes', ['id' => $response['id']]);
    }

    /** @test */
    public function should_note_list()
    {
        $user = factory(User::class)->create();
        factory(Note::class, 100)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/notes');

        $response->assertOk();
        $this->assertCount(20, $response['data']);
    }
}
