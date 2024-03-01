<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Manufacturer;
use App\Models\SiteTags;


use App\Models\LocationServiceArea;

use App\Models\JobNoteModel;
use App\Models\JobModel;
use App\Models\Ticket;
use App\Models\Technician;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Display a listing of the tickets
    public function index()
    {
        $servicearea = LocationServiceArea::all();
         $manufacturer = Manufacturer::all();
          $technicianrole = User::where('role','technician')->get();
        $totalCalls = JobModel::count();
        $inProgress = JobModel::where('status', 'in_progress')->count();
        $opened = JobModel::where('status', 'open')->count();
        $complete = JobModel::where('status', 'closed')->count();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $technicians = JobModel::with('jobdetailsinfo','jobassignname')->orderBy('created_at', 'desc')->get();
        //dd($technicians->jobdetailsinfo->manufacturername[15]);

        return view('tickets.index', [
            'tickets' => $tickets,
            'manufacturer' => $manufacturer,
            'technicianrole' => $technicianrole,
            'technicians' => $technicians,
            'servicearea' => $servicearea,
            'totalCalls' => $totalCalls,
            'inProgress' => $inProgress,
            'opened' => $opened,
            'complete' => $complete
        ]);
    }

    // Show the form for creating a new ticket
    public function create()
    {
        $technicians = Technician::all(); // Fetch all users
        $users = User::all(); // Fetch all users
        return view('tickets.create', compact('users', 'technicians'));
    }

    // Store a newly created ticket in the database
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'status' => 'required',
            'priority' => 'required',
            'customer_id' => 'required',
            'technician_id' => 'required',
            'subject' => 'required|max:255',
            'description' => 'required',
            'ticket_number' => 'required',
            // Add validation rules for other fields
        ]);

        // Create a new ticket
        $ticket = Ticket::create([
            'status' => $validatedData['status'],
            'priority' => $validatedData['priority'],
            'customer_id' => $validatedData['customer_id'],
            'technician_id' => $validatedData['technician_id'],
            'subject' => $validatedData['subject'],
            'description' => $validatedData['description'],
            'ticket_number' => $validatedData['ticket_number'],
            // Add other fields here based on your database schema
        ]);

        // Redirect to the ticket details page or any other appropriate page
        return redirect()->route('tickets.index', $ticket->id);
    }


    // Display the specified ticket
    public function show($id)
    {
        $technicians = JobModel::find($id);
        $ticket = JobModel::with('user','jobactivity')->findOrFail($id);
        $techniciansnotes = JobNoteModel::where('job_id', '=', $id)
            ->Leftjoin('users', 'users.id', '=', 'job_notes.added_by')
            ->select('job_notes.*', 'users.name', 'users.user_image')
            ->get();
          //  $sitetags='';
           $name=$technicians->tag_ids;
            // dd($name);
                 $names=explode(',',$name);
                // dd($names);
               //  foreach($names as $items)
                // {
               $Sitetagnames=SiteTags::where('tag_id' ,  $names)->get();
                 // dd($Sitetagnames);
                // }
              //dd($techniciansnotes);
              return view('tickets.show', ['ticket' => $ticket,'Sitetagnames' => $Sitetagnames, 'technicians' => $technicians, 'techniciansnotes' => $techniciansnotes,]);
       }
    // Show the form for editing the specified ticket 
    public function edit($id)
    {
        $technicians = Technician::all(); // Fetch all users
        $users = User::all(); // Fetch all users
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', ['ticket' => $ticket], compact('users', 'technicians'));
    }

    // Update the specified ticket in the database
    public function update(Request $request, $id)
    {
        // Validate the updated data
        $validatedData = $request->validate([
            'status' => 'required',
            'priority' => 'required',
            'customer_id' => 'required',
            'technician_id' => 'required',
            'subject' => 'required|max:255',
            'description' => 'required',
            'ticket_number' => 'required',
            // Add validation rules for other fields
        ]);

        // Find the ticket by ID
        $ticket = Ticket::findOrFail($id);

        // Update the ticket with the validated data
        $ticket->update([
            'status' => $validatedData['status'],
            'priority' => $validatedData['priority'],
            'customer_id' => $validatedData['customer_id'],
            'technician_id' => $validatedData['technician_id'],
            'subject' => $validatedData['subject'],
            'description' => $validatedData['description'],
            'ticket_number' => $validatedData['ticket_number'],
            // Add other fields here based on your database schema
        ]);

        // Redirect to the ticket details page or any other appropriate page
        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully');
    }

    // Remove the specified ticket from the database
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        // Redirect to the ticket list or any other appropriate page
        return redirect()->route('tickets.index');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        // Logic to fetch users/teams for dropdown list
        $users = User::all(); // Example: Fetch all users from the User model

        // Perform authorization check
        if (!auth()->user()->can('assign-ticket')) {
            abort(403, 'Unauthorized action');
        }

        return view('tickets.assign', compact('ticket', 'users'));
    }

    public function updateAssign(Request $request, Ticket $ticket)
    {
        // Perform authorization check
        if (!auth()->user()->can('assign-ticket')) {
            abort(403, 'Unauthorized action');
        }

        // Validate incoming request data
        $validatedData = $request->validate([
            'assigned_user_id' => 'required|exists:users,id', // Assuming 'assigned_user_id' is the field for user/team selection
            'assigned_team_id' => 'nullable|exists:teams,id', // Assuming 'assigned_team_id' is the field for team selection (if applicable)
            // Add more validation rules if needed
        ]);

        // Update the ticket assignment
        $ticket->assigned_user_id = $validatedData['assigned_user_id'];
        $ticket->assigned_team_id = $validatedData['assigned_team_id']; // If team assignment is applicable
        // Any other related information updates

        $ticket->save();

        // Redirect or show success message
        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket assigned successfully');
    }

    public function techniciannotestore(Request $request)
    {
       // dd($request->all());
        // Validate the incoming request data
        $request->validate([
         
            
        ]);

       
        $jobNote = new JobNoteModel([
            'user_id' => $request->technician_id,
            'job_id' => $request->id,
            'note' => $request->note,
            'added_by' => Auth::id(), 
            'updated_by' => Auth::id(), 
            
        ]);

       
        $jobNote->save();

        
        return redirect()->back()->with('success', 'Job note created successfully');
    }
}