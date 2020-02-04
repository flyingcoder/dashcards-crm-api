<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index()
    {
        return auth()->user()->getNotes();
    }

    public function store()
    {
        return auth()->user()->addNotes();
    }

    public function collaborators($id)
    {
        request()->validate(['users_id' => 'required|array']);

        $note = Note::findOrFail($id);

        $note->users()->sync(request()->users_id);

        return $note->collaborators();
    }

    public function pinning($id, $action)
    {
        $note = Note::findOrFail($id);

        return $note->pinning($action);
    }
    
    public function show(Notes $notes)
    {
        //
    }

    public function update($id)
    {
        $note = Note::findOrFail($id);

        return $note->updateNote();
    }

    public function delete($id)
    {
        $note = Note::findOrFail($id);

        if($note->delete()){
            return response()->json(['message' => 'Note successfully deleted.'], 200);
        }

        return response()->json(['error' => $e->getmessage()], 422);
    }
}
