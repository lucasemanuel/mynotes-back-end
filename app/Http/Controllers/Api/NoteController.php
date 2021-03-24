<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreRequest;
use App\Http\Requests\Notes\UpdateRequest;
use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $notes = $user->notes()
            ->when($request->text, function ($query, $body) {
                $query->where('body', 'like', "%{$body}%");
            })->when($request->favorite, function ($query, $favorite) {
                $query->where('is_favorite', '=', (bool) $favorite);
            })->orderByDesc('updated_at')->paginate(32);

        return response($notes);
    }

    public function store(StoreRequest $request)
    {
        $user = auth()->user();
        if ($user->notes->count() >= Note::LIMIT_NOTE_BY_USER) {
            return response([
                'message' => 'Você atingiu o limite de notas, não possível registrar uma nova nota.'
            ], 400);
        }

        $note = new Note();
        $note->body = $request->body;
        $note->is_favorite = (bool) $request->input('is_favorite', 0);
        $note->user_id = $user->id;
        $note->save();

        return response($note, 201);
    }

    public function show(Note $note)
    {
        return response($note);
    }

    public function update(UpdateRequest $request, Note $note)
    {
        $note->fill($request->all());
        $note->save();

        return response($note);
    }

    public function mark(Note $note)
    {
        $note->is_favorite = !$note->is_favorite;
        $note->save();

        return response($note);
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return response(null, 204);
    }
}
