<?php

namespace App\Http\Controllers;

use App\Note;

class NoteController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return auth()->user()->getNotes();
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return auth()->user()->addNotes();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function collaborators($id)
    {
        request()->validate(['users_id' => 'required|array']);

        $note = Note::findOrFail($id);

        $note->users()->sync(request()->users_id);

        return $note->collaborators();
    }

    /**
     * @param $id
     * @param $action
     * @return mixed
     */
    public function pinning($id, $action)
    {
        $note = Note::findOrFail($id);

        return $note->pinning($action);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $note = Note::findOrFail($id);

        return $note->updateNote();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $note = Note::findOrFail($id);

        if ($note->delete()) {
            return response()->json(['message' => 'Note successfully deleted.'], 200);
        }
        return response()->json(['error' => 'Cant delete this note. Please try again.'], 422);
    }
}
