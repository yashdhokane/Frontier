<?php

namespace App\Http\Controllers;

use App\Models\CustomerUserAddress;
use App\Models\JobActivity;
use App\Models\JobAssign;
use App\Models\Jobfields;
use App\Models\JobFile;
use App\Models\Payment;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Manufacturer;
use App\Models\SiteTags;


use App\Models\LocationServiceArea;

use App\Models\JobNoteModel;
use App\Models\JobModel;
use App\Models\Schedule;
use App\Models\SiteJobFields;
use App\Models\SiteLeadSource;
use App\Models\Ticket;
use App\Models\Technician;
use App\Models\JobProduct;

use App\Models\JobServices;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    // Display a listing of the tickets
    public function index()
    {
        $servicearea = LocationServiceArea::all();
        $manufacturer = Manufacturer::all();
        $technicianrole = User::where('role', 'technician')->get();
        $totalCalls = JobModel::count();
        $inProgress = JobModel::where('status', 'in_progress')->count();
        $opened = JobModel::where('status', 'open')->count();
        $complete = JobModel::where('status', 'closed')->count();
        $status = JobModel::all();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $technicians = JobModel::with('jobdetailsinfo', 'jobassignname')->orderBy('created_at', 'desc')->get();

        return view('tickets.index', [
            'tickets' => $tickets,
            'status' => $status,
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
        $technicians = JobModel::with('jobassignname', 'JobAssign', 'usertechnician', 'addedby', 'jobfieldname')->find($id);

        if(!$technicians){
           return view('404');
        }

        $fieldIds = explode(',', $technicians->job_field_ids);
        $jobFields = Jobfields::whereIn('field_id', $fieldIds)->get();
        $Payment = Payment::where('job_id', $id)->first();

        $jobproduct = JobProduct::where('job_id', $id)->get();
        $jobservice = JobServices::where('job_id', $id)->get();


        $ticket = JobModel::with('user', 'jobactivity')->findOrFail($id);
        $techniciansnotes = JobNoteModel::where('job_id', '=', $id)
            ->Leftjoin('users', 'users.id', '=', 'job_notes.added_by')
            ->select('job_notes.*', 'users.name', 'users.user_image')
            ->get();


        $name = $technicians->tag_ids;

        $names = explode(',', $name);

        $Sitetagnames = SiteTags::whereIn('tag_id', $names)->get();
        $customer_tag = SiteTags::all();


        $namejobs = $technicians->job_field_ids;

        $namesjobtag = explode(',', $namejobs);

        $jobtagnames = SiteJobFields::whereIn('field_id', $namesjobtag)->get();

        $job_tag = SiteJobFields::all();

        $lead = User::where('id', $technicians->customer_id)->first();

        if ($lead) {
            $lead_id = $lead->source_id;
            $lead_id = $lead->source_id;
        } else {

            $lead_id = null;
        }


        $leadone = explode(',', $lead_id);

        $source = SiteLeadSource::whereIn('source_id', $leadone)->get();

        $leadsource = SiteLeadSource::all();

        $activity = JobActivity::with('user')->where('job_id', $id)->latest()->get();

        $files = JobFile::where('job_id', $id)->latest()->get();

        $schedule = JobAssign::where('job_id', $id)->first();

        $jobTimings = App::make('JobTimingManager')->getJobTimings($id);

        // travel time 
        $currentDate = Carbon::today('Asia/Kolkata');
        $previousJob = JobModel::where('technician_id', $technicians->technician_id)
                    ->whereDate('created_at', $currentDate)
                    ->exists();
        $tech_add = CustomerUserAddress::where('user_id', $technicians->technician_id)->first();
        if(!$previousJob){
           $address = ($previousJob->latitude ?? 0) . ',' . ($previousJob->longitude ?? 0);
        }else{
        $address = ($tech_add->latitude ?? 0) . ',' . ($tech_add->longitude ?? 0);
        }
        $customer_address = ($technicians->latitude ?? 0) . ',' . ($technicians->longitude ?? 0);

        $origin = $address;
        $destination = $customer_address;

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'destinations' => $destination,
            'origins' => $origin,
            'key' => 'AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo',
        ]);
        $travelTime = 0;
        $data = $response->json();
        if ($response->successful()) {
            if ($data['status'] === 'OK' && isset($data['rows'][0]['elements'][0]['duration'])) {
                // Extract duration
                $travelTime = $data['rows'][0]['elements'][0]['duration']['text'];
            }
        } else {
            $travelTime = 0;
        }

        return view('tickets.show', ['Payment' => $Payment, 'jobservice' => $jobservice, 'jobproduct' => $jobproduct, 'jobFields' => $jobFields, 'ticket' => $ticket, 'Sitetagnames' => $Sitetagnames, 'technicians' => $technicians, 'techniciansnotes' => $techniciansnotes, 'customer_tag' => $customer_tag, 'job_tag' => $job_tag, 'jobtagnames' => $jobtagnames, 'leadsource' => $leadsource, 'source' => $source, 'activity' => $activity, 'files' => $files, 'schedule' => $schedule, 'jobTimings' => $jobTimings, 'travelTime' => $travelTime]);
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
        // Create a new job note
        $jobNote = new JobNoteModel([
            'user_id' => $request->technician_id,
            'job_id' => $request->id,
            'note' => $request->note,
            'added_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $jobNote->save();

        // Check if the job note was successfully saved
        if ($jobNote) {
            $activity = 'Job Note added';
            app('JobActivityManager')->addJobActivity($request->id, $activity);
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Job note created successfully');
    }

    public function addCustomerTags(Request $request, $id)
    {

        // Serialize manufacturer_ids manually
        $job = JobModel::find($id);

        // Get the existing tag_ids and convert them into an array
        $existingTags = explode(',', $job->tag_ids);

        // Add the new tag(s) to the array
        $newTags = $request->customer_tags;
        $allTags = array_merge($existingTags, $newTags);

        // Remove duplicate tags (optional)
        $allTags = array_unique($allTags);

        // Convert the array back to a comma-separated string
        $customer_tags_string = implode(',', $allTags);

        // Update the tag_ids attribute with the combined tags
        $job->tag_ids = $customer_tags_string;

        // Save the changes
        $job->save();

        $activity = 'Customer Tags Updated';
        app('JobActivityManager')->addJobActivity($id, $activity);


        return redirect()->back()->with('success', 'Cusomer tags added successfully');
    }
    public function job_tags(Request $request, $id)
    {

        // Serialize manufacturer_ids manually
        $job = JobModel::find($id);

        // Get the existing tag_ids and convert them into an array
        $existingTags = explode(',', $job->job_field_ids);

        // Add the new tag(s) to the array
        $newTags = $request->job_tags;
        $allTags = array_merge($existingTags, $newTags);

        // Remove duplicate tags (optional)
        $allTags = array_unique($allTags);

        // Convert the array back to a comma-separated string
        $customer_tags_string = implode(',', $allTags);

        // Update the tag_ids attribute with the combined tags
        $job->job_field_ids = $customer_tags_string;

        // Save the changes
        $job->save();

        $activity = 'Job Tags Updated';
        app('JobActivityManager')->addJobActivity($id, $activity);


        return redirect()->back()->with('success', 'Job tags added successfully');
    }


    public function attachment(Request $request, $id)
    {
        $file = new JobFile();
        $file->job_id = $id;
        $file->user_id = Auth::user()->id;
        $file->save();

        if ($request->hasFile('attachment')) {
            $uploadedFile = $request->file('attachment');

            // Check if the file upload was successful
            if ($uploadedFile->isValid()) {
                // Generate a unique filename
                $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                $imageName1 = $uploadedFile->getClientOriginalName();

                // Construct the full path for the directory
                $directoryPath = public_path('images/users/' . Auth::user()->id);

                // Ensure the directory exists; if not, create it
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true);
                }

                // Move the uploaded file to the unique directory
                if ($uploadedFile->move($directoryPath, $imageName1)) {
                    // Save file details to the database
                    $file->filename = $imageName1;
                    $file->path = $directoryPath;
                    $file->type = $uploadedFile->getClientMimeType();
                    $file->save();

                    $activity = 'Attachments Uploaded';
                    app('JobActivityManager')->addJobActivity($id, $activity);

                    return redirect()->back()->with('success', 'Attachment added successfully');
                } else {
                    return redirect()->back()->with('error', 'Failed to move uploaded file');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid file upload');
            }
        } else {
            return redirect()->back()->with('error', 'No file uploaded');
        }
    }


    public function leadSource(Request $request, $id)
    {
        $technicians = JobModel::with('jobassignname', 'usertechnician')->find($id);
        $job = User::where('id', $technicians->customer_id)->first();


        $existingTags = explode(',', $job->source_id);

        // Add the new tag(s) to the array
        $newTags = $request->lead_source;
        $allTags = array_merge($existingTags, $newTags);

        // Remove duplicate tags (optional)
        $allTags = array_unique($allTags);

        // Convert the array back to a comma-separated string
        $customer_tags_string = implode(',', $allTags);
        // Update the tag_ids attribute with the combined tags
        $job->source_id = $customer_tags_string;

        // Save the changes
        $job->save();

        $activity = 'Lead Source Updated';
        app('JobActivityManager')->addJobActivity($id, $activity);



        return redirect()->back()->with('success', 'Job tags added successfully');
    }
}
