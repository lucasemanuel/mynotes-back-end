<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreRequest;
use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notes = $user->notes()
            ->orderByDesc('updated_at')
            ->paginate(20);

        return response($notes);
    }

    public function store(StoreRequest $request)
    {
        $note = new Note();
        $note->body = $request->body;
        $note->is_favorite = (bool) $request->input('is_favorite', 0);
        $note->user_id = auth()->user()->id;
        $note->save();

        return response($note->toArray(), 201);
    }

    public function show($id)
    {
        $note = Note::with('user')->where(['id' => $id])->first();

        return response($note);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
