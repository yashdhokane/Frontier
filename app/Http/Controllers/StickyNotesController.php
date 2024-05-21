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

        return redirect()->back()->with('success','Sticky note created successfully');
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

        return redirect()->route('sticky_notes')->with('success','Sticky note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $note = StickyNotes::findOrFail($id);

         $note->delete();

         return redirect()->back()->with('success','Sticky note deleted successfully');

    }

    public function edit(Request $request, $id)
    {
       $color = ColorCode::all();
        $note = StickyNotes::findOrFail($id);

        return view('stickynotes.edit', compact('color','note'));
    }

}
