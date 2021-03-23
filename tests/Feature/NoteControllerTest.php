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
    public function should_list_x_notes_per_page()
    {
        $user = factory(User::class)->create();
        factory(Note::class, 200)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/notes');

        $response->assertOk();
        $response->assertJsonFragment(['per_page', 'total', 'current_page', 'last_page']);
        $this->assertCount(64, $response['per_page']);
    }

    /** @test */
    public function should_update_note()
    {
        $user = factory(User::class)->create();
        $note = factory(Note::class)->create([
            'user_id' => $user->id
        ]);

        $text = "Meu texto";
        $response = $this->actingAs($user, 'api')
            ->putJson("/api/notes/$note->id", [
                'body' => $text
            ]);

        $response->assertOk();
        $response->assertJson($note->toArray());
        $this->assertDatabaseHas('notes', ['body' => $text, 'id' => $note->id]);
    }

    /** @test */
    public function should_mark_note_as_favorite()
    {
        $user = factory(User::class)->create();
        $note = factory(Note::class)->create([
            'user_id' => $user->id,
            'is_favorite' => false
        ]);

        $response = $this->actingAs($user, 'api')
            ->patchJson("/api/notes/$note->id");

        $response->assertOk();
        $response->assertJson($note->toArray());
        $this->assertDatabaseHas('notes', ['is_favorite' => true, 'id' => $note->id]);
    }

    /** @test */
    public function should_mark_off_note_as_favorite()
    {
        $user = factory(User::class)->create();
        $note = factory(Note::class)->create([
            'user_id' => $user->id,
            'is_favorite' => true
        ]);

        $response = $this->actingAs($user, 'api')
            ->patchJson("/api/notes/$note->id");

        $response->assertOk();
        $response->assertJson($note->toArray());
        $this->assertDatabaseHas('notes', ['is_favorite' => false, 'id' => $note->id]);
    }

    /** @test */
    public function should_delete_a_note()
    {
        $user = factory(User::class)->create();
        $notes = factory(Note::class, 10)->create([
            'user_id' => $user->id,
        ]);

        $id = $notes[rand(0, 9)]->id;
        $response = $this->actingAs($user, 'api')
            ->deleteJson("/api/notes/$id");

        $response->assertNoContent();
        $this->assertDatabaseMissing('notes', ['id' => $id]);
    }

    /** @test */
    public function should_return_note_list_by_content_search()
    {
        $user = factory(User::class)->create();

        $amount = 3;
        $query = 'MEU SUPER TEXTO!!!';

        factory(Note::class, 10)->create([
            'user_id' => $user->id,
        ]);
        factory(Note::class, $amount)->create([
            'user_id' => $user->id,
            'body' => $query
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson("/api/notes?text=$query");

        $response->assertOk();
        $response->assertJsonCount($amount);
    }

    /** @test */
    public function should_return_only_favorite_notes()
    {
        $user = factory(User::class)->create();

        $amount = 8;
        factory(Note::class, 7)->create([
            'user_id' => $user->id,
            'is_favorite' => false
        ]);
        factory(Note::class, $amount)->create([
            'user_id' => $user->id,
            'is_favorite' => true
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson("/api/notes?favorite=true");

        $response->assertOk();
        $response->assertJsonCount($amount);
    }
}
