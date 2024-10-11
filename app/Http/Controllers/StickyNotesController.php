<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\StickyNotes;
use Illuminate\Http\Request;

class StickyNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $color = ColorCode::all();
        $note = StickyNotes::all();

        return view('stickynotes.index', compact('color','note'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_auth = auth()->user()->id;
        $note = new StickyNotes();

        $note->user_id = $user_auth;
        $note->note = $request->note;
        $note->color_code = $request->color_code;

        $note->save();

        return redirect()->back()->with('success','The sticky note has been created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
        $note = StickyNotes::findOrFail($id);
        $note->note = $request->note;
        $note->color_code = $request->color_code;

        $note->update();

        return redirect()->route('sticky_notes')->with('success','The sticky note has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $note = StickyNotes::findOrFail($id);

         $note->delete();

         return redirect()->back()->with('success','The sticky note has been deleted successfully.');

    }

    public function edit(Request $request, $id)
    {
       $color = ColorCode::all();
        $note = StickyNotes::findOrFail($id);

        return view('stickynotes.edit', compact('color','note'));
    }

    public function storeColorNote(Request $request)
    {
        
        $user_auth = auth()->user()->id;

        // Create a new StickyNote (or handle it as needed)
        $stickyNote = new StickyNotes();

        $stickyNote->color_code = $request->color_code;
        $stickyNote->note = $request->note;
        $stickyNote->user_id = $user_auth;

        $stickyNote->save();
        $stickyNotes = StickyNotes::all();
        // Return a response
        return response()->json($stickyNotes);
    }


        public function getNote(Request $request)
        {
            // Fetch the note details by ID
            $note = StickyNotes::where('note_id', $request->id)->first();

            // Assuming you want to include color options in the response
            $color = ColorCode::all();

            // Return the note details and color options as JSON
            return response()->json([
                'note_id' => $note->note_id,
                'note' => $note->note,
                'color_code' => $note->color_code,
                'color' => $color
            ]);
        }


        public function updateColorNote(Request $request)
        {
            
            $user_auth = auth()->user()->id;
    
            // Create a new StickyNote (or handle it as needed)
            $stickyNote = StickyNotes::find($request->input('note_id'));
    
            $stickyNote->color_code = $request->color_code;
            $stickyNote->note = $request->note;
            $stickyNote->user_id = $user_auth;
    
            $stickyNote->save();
            $stickyNotes = StickyNotes::all();
            // Return a response
            return response()->json($stickyNotes);
        }
    

        public function deleteNote(Request $request)
        {
            
            $stickyNote = StickyNotes::find($request->input('id'));
    
            $stickyNote->delete();

            $stickyNotes = StickyNotes::all();
            // Return a response
            return response()->json($stickyNotes);
        }

}
