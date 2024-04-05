<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event = Event::with('technician')->get();
        
        $technicianrole = User::where('role', 'technician')->get();

        return view('events.index',compact('event','technicianrole'));
    }

   
    public function destroy(Request $request, $id)
    {
        $event = Event::find($id);

        $event->delete();

        return redirect()->back()->with('success','Successfully deleted');
    }
}
