<?php
namespace App\Http\Controllers\Backend;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class NoteController extends Controller
{
    // Get notes for DataTables (filtered by user ID)
    public function index($userId)
    {
        // Fetch notes for the specified user, including admin details
        $notes = Note::where('user_id', $userId)->with('admin')->get();

        // Return data for DataTables with admin name and action buttons
        return Datatables::of($notes)
            ->addColumn('staff', 'backend.user.notes.include.__staff')
            ->addColumn('action', 'backend.user.notes.include.__action')
            ->rawColumns(['staff', 'action'])
            ->make(true);
    }

    // Create a new note for the user
    public function create(Request $request, $userId)
    {
        // Validate the request input
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        // Create the note with user_id, description, and admin_id
        Note::create([
            'user_id' => $userId,
            'description' => $request->notes,
            'admin_id' => auth()->id(), // Admin ID from authentication
        ]);

        return redirect()->back()->with('success', 'Note added successfully.');
    }

    // Edit a note
    public function edit($id)
    {
        // Find the note by its ID
        $note = Note::findOrFail($id);
        // Load the edit view with the note data
        return view('backend.user.notes.include.__edit_notes', compact('note'));
    }

    // Update a note
    public function update(Request $request, $id)
    {
        // Validate the updated note content
        $request->validate([
            'notes' => 'required|string',
        ]);

        // Find the note and update its description
        $note = Note::findOrFail($id);
        $note->description = $request->notes;
        $note->save();

        // Return success response for AJAX request
        return response()->json(['success' => 'Note updated successfully.']);
    }

    // Delete a note
    public function destroy($id)
    {
        // Find the note by its ID
        $note = Note::findOrFail($id);

        try {
            // Try to delete the note
            $note->delete();
            return response()->json(['success' => __('Note deleted successfully.')]);
        } catch (\Exception $e) {
            // Return error response if deletion fails
            return response()->json(['error' => __('Failed to delete the note. Please try again.')], 500);
        }
    }
}
