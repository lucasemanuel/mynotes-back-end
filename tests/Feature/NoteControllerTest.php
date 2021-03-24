<?php

namespace Tests\Feature;

use App\Note;
use App\User;
use Faker\Provider\Lorem;
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
        factory(Note::class, Note::LIMIT_NOTE_BY_USER)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/notes');

        $response->assertOk();
        $response->assertJsonStructure(['total', 'per_page', 'data', 'current_page', 'last_page']);
        $this->assertCount(32, $response['data']);
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
        $this->assertCount($amount, $response['data']);
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
        $this->assertCount($amount, $response['data']);
    }

    /** @test */
    public function should_return_error_when_reaching_note_limit_per_user()
    {
        $user = factory(User::class)->create();

        factory(Note::class, Note::LIMIT_NOTE_BY_USER)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/notes', [
                'body' => 'Mais uma nota.'
            ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function should_return_unprocessable_entity_when_incorrect_data_is_sent()
    {
        $user = factory(User::class)->create();

        ($this->actingAs($user, 'api')
            ->postJson('/api/notes', [
                'body' => Lorem::text(100),
                'is_favorite' => 'no_boolean',
            ])
        )->assertStatus(422);

        ($this->actingAs($user, 'api')
            ->postJson('/api/notes', [
                'body' => Lorem::text(10000),
                'is_favorite' => true,
            ])
        )->assertStatus(422);
    }
}
