<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = Auth::id();
        try {
            $notes = Note::where('user_id', $id)->paginate(6);
            // ->get();
            return new NoteCollection($notes);

            
            // response()->json([
            //     'status' => true,
            //     'message' => 'Notes fetched successfully',
            //     'notes' => $notes
            // ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addNote(Request $request)
    {
        $id = Auth::id();
        $note = new Note();
        $validated = $request->validate([
            'note_label' => 'required',
            'note_body' => 'required'
        ]);
        $note->note_label = $validated['note_label'];
        $note->note_body = $validated['note_body'];
        $note->user_id = $id;


        try {
            if ($note->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Note added successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Note could not be added'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function deleteNote($id)
    {
        $user_id = Auth::id();
        $note = Note::where('user_id', $user_id)->find($id);

        if (!$note) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Note not found',

            ], 404);
        }
        try {
            $note->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Note deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function updateNote(Request $request, $id)
    {
        $user_id = Auth::id();

        $note = Note::where('user_id', $user_id)->find($id);

        if (!$note) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Note not found'
            ], 404);
        }
        try {

            $note->update([
                'note_label' => $request->note_label,
                'note_body' => $request->note_body
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Note updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
