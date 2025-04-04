<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\CustomerUserAddress;
use App\Models\FlagJob;
use App\Models\JobActivity;
use App\Models\JobAssign;
use App\Models\Jobfields;
use App\Models\JobFile;
use Illuminate\Support\Facades\Session;

use App\Models\Payment;
use App\Models\JobTechEvents;


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
use App\Models\TimeZone;
use App\Models\UserAppliances;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    // Display a listing of the tickets
    public function index()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 32;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $servicearea = LocationServiceArea::all();
        $manufacturer = Manufacturer::all();
        $technicianrole = User::where('role', 'technician')->where('status', 'active')->get();
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

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 34;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $technicians = JobModel::with('jobassignname', 'JobTechEvent', 'JobAssign', 'usertechnician', 'addedby', 'jobfieldname')->find($id);

        if (!$technicians) {
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

        $jobtagnames = SiteJobFields::whereIn('field_id', $namesjobtag)->orderBy('field_name', 'asc')->get();

        $job_tag = SiteJobFields::orderBy('field_name', 'asc')->get();

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

        $schedule = JobAssign::where('job_id', $id)->where('assign_status', 'active')->first();

        $jobTimings = App::make('JobTimingManager')->getJobTimings($id);

        // travel time

        $currentJobDate = $technicians->created_at;

        // Define the start and end of the current job's date
        $startOfDay = Carbon::parse($currentJobDate)->startOfDay();
        $endOfDay = Carbon::parse($currentJobDate)->endOfDay();

        // Find the previous job for the same technician created before the current job's creation time on the same day
        $previousJob = JobModel::where('technician_id', $technicians->technician_id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where('created_at', '<', $currentJobDate)
            ->orderBy('created_at', 'desc')
            ->first();

        $tech_add = CustomerUserAddress::where('user_id', $technicians->technician_id)->first();

        if ($previousJob) {
            $address = ($previousJob->latitude ?? 0) . ',' . ($previousJob->longitude ?? 0);
        } else {
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

        $checkSchedule = Schedule::where('job_id', $id)->first();

        $jobAssigns = JobAssign::where('job_id', $id)->get();
        $assignedJobs = $jobAssigns->count() > 1 ? $jobAssigns : null;

        $job_appliance = UserAppliances::with('appliance', 'manufacturer')->where('user_id', $ticket->customer_id)->get();

        $appliances = DB::table('appliance_type')->get();

        $manufacturers = DB::table('manufacturers')->get();
        $d = Carbon::parse($checkSchedule->start_date_time)->format('Y-m-d');
        $t = Carbon::parse($checkSchedule->start_date_time)->format('h:i A');
        $date = $d;
        $time = str_replace(" ", ":00 ", $t);
        $dateTime = Carbon::parse("$date $time");
        $datenew = Carbon::parse($date);
        $currentDay = $datenew->format('l');
        $currentDayLower = strtolower($currentDay);
        // Query the business hours for the given day
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        // Calculate time intervals (example)
        $timeIntervals = [];
        $current = strtotime($hours->start_time);
        $end = strtotime($hours->end_time);
        $interval = 30 * 60; // Interval in seconds (30 minutes)

        while ($current <= $end) {
            $timeIntervals[] = date('H:i', $current);
            $current += $interval;
        }

        $int = Session::get('time_interval');

        $startDateTime = $checkSchedule->start_date_time ? Carbon::parse($checkSchedule->start_date_time) : null;
        $startDateTime2 = $checkSchedule->end_date_time ? Carbon::parse($checkSchedule->end_date_time) : null;
        if ($startDateTime && isset($int)) {
            // Add the interval to the parsed time
            $startDateTime->addHours($int);
        }

        if ($startDateTime2 && isset($int)) {
            // Add the interval to the parsed time
            $startDateTime2->addHours($int);
        }

        $fromDate = $startDateTime ? $startDateTime->format('h:i A') : null;
        $toDate = $startDateTime2 ? $startDateTime2->format('h:i A') : null;

        $flag = FlagJob::all();
        $notes = JobNoteModel::where('job_id', '=', $id)
        ->where('is_flagged', '=', 'yes')
        ->leftJoin('users', 'users.id', '=', 'job_notes.added_by')
        ->select('job_notes.*', 'users.name', 'users.user_image')
        ->latest()
        ->first();
    
        

        return view('tickets.show', ['Payment' => $Payment, 'jobservice' => $jobservice, 'jobproduct' => $jobproduct, 'jobFields' => $jobFields, 'ticket' => $ticket, 'Sitetagnames' => $Sitetagnames, 'technicians' => $technicians, 'techniciansnotes' => $techniciansnotes, 'customer_tag' => $customer_tag, 'job_tag' => $job_tag, 'jobtagnames' => $jobtagnames, 'leadsource' => $leadsource, 'source' => $source, 'activity' => $activity, 'files' => $files, 'schedule' => $schedule, 'jobTimings' => $jobTimings, 'travelTime' => $travelTime, 'checkSchedule' => $checkSchedule, 'assignedJobs' => $assignedJobs, 'job_appliance' => $job_appliance, 'appliances' => $appliances, 'manufacturers' => $manufacturers, 'timeIntervals' => $timeIntervals, 'dateTime' => $dateTime, 'date' => $date, 'fromDate' => $fromDate, 'toDate' => $toDate, 'flag' => $flag, 'notes' => $notes]);
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
        return redirect()->route('tickets.index')->with('success', 'The job has been updated successfully.');
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
        return redirect()->route('tickets.show', $ticket->id)->with('success', 'The job has been assigned successfully.');
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
        return redirect()->back()->with('success', 'The job note has been created successfully.');
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


        return redirect()->back()->with('success', 'The customer tags have been added successfully.');
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


        return redirect()->back()->with('success', 'The job tags have been added successfully.');
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

                    return redirect()->back()->with('success', 'The attachment has been added successfully.');
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



        return redirect()->back()->with('success', 'The job tags have been added successfully.');
    }


    public function update_approval_for_pending_job(Request $request)
    {
        // Find the JobModel by ID
        $jobModel = JobModel::find($request->job_id);

        // Find the JobTechEvents model by the same job_id
        $jobTechEvents = JobTechEvents::where('job_id', $request->job_id)->first();

        if ($request->has('approve_pending_job') && $request->approve_pending_job == 'on') {
            $jobModel->status = 'closed';
            $jobModel->closed_by = Auth::id();
            $timezone_name = Session::get('timezone_name');
            $jobModel->closed_date = Carbon::now($timezone_name);
        } elseif (!$request->has('approve_pending_job') && $request->has('job_id')) {
            $jobModel->status = 'open';
        }

        // Add the comment to the JobTechEvents model
        if ($jobTechEvents) {
            $jobTechEvents->closed_job_comment = $request->comment;
            $jobTechEvents->save();
        }

        // Save the updated status for JobModel
        $jobModel->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'The approve job status has been updated successfully.');
    }


    public function updateJobSettings(Request $request, $id)
    {
        $job = JobModel::find($id);
        $schedule = Schedule::where('job_id', $id)->first();

        // Update the values based on form submission
        $job->is_confirmed = $request->input('job_confirmed') === 'on' ? 'yes' : 'no';
        $job->status = $request->input('job_closed') === 'on' ? 'closed' : 'open';
        $schedule->show_on_schedule = $request->input('job_schedule') === 'on' ? 'yes' : 'no';

        // Save the changes
        $job->save();
        $schedule->save();

        return redirect()->back()->with('success', 'The job settings have been updated successfully.');
    }


    public function showiframe($id)
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 34;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $technicians = JobModel::with('jobassignname', 'JobTechEvent', 'JobAssign', 'usertechnician', 'addedby', 'jobfieldname')->find($id);

        if (!$technicians) {
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

        $schedule = JobAssign::where('job_id', $id)->where('assign_status', 'active')->first();

        $jobTimings = App::make('JobTimingManager')->getJobTimings($id);

        // travel time

        $currentJobDate = $technicians->created_at;

        // Define the start and end of the current job's date
        $startOfDay = Carbon::parse($currentJobDate)->startOfDay();
        $endOfDay = Carbon::parse($currentJobDate)->endOfDay();

        // Find the previous job for the same technician created before the current job's creation time on the same day
        $previousJob = JobModel::where('technician_id', $technicians->technician_id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where('created_at', '<', $currentJobDate)
            ->orderBy('created_at', 'desc')
            ->first();

        $tech_add = CustomerUserAddress::where('user_id', $technicians->technician_id)->first();

        if ($previousJob) {
            $address = ($previousJob->latitude ?? 0) . ',' . ($previousJob->longitude ?? 0);
        } else {
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

        $checkSchedule = Schedule::where('job_id', $id)->first();

        $jobAssigns = JobAssign::where('job_id', $id)->get();
        $assignedJobs = $jobAssigns->count() > 1 ? $jobAssigns : null;

        $job_appliance = UserAppliances::with('appliance', 'manufacturer')->where('user_id', $ticket->customer_id)->get();

        $appliances = DB::table('appliance_type')->get();

        $manufacturers = DB::table('manufacturers')->get();
        $d = Carbon::parse($checkSchedule->start_date_time)->format('Y-m-d');
        $t = Carbon::parse($checkSchedule->start_date_time)->format('h:i A');
        $date = $d;
        $time = str_replace(" ", ":00 ", $t);
        $dateTime = Carbon::parse("$date $time");
        $datenew = Carbon::parse($date);
        $currentDay = $datenew->format('l');
        $currentDayLower = strtolower($currentDay);
        // Query the business hours for the given day
        $hours = BusinessHours::where(
            'day',
            $currentDayLower
        )->first();

        // Calculate time intervals (example)
        $timeIntervals = [];
        $current = strtotime($hours->start_time);
        $end = strtotime($hours->end_time);
        $interval = 30 * 60; // Interval in seconds (30 minutes)

        while ($current <= $end) {
            $timeIntervals[] = date('H:i', $current);
            $current += $interval;
        }

        $int = Session::get('time_interval');

        $startDateTime = $checkSchedule->start_date_time ? Carbon::parse($checkSchedule->start_date_time) : null;
        $startDateTime2 = $checkSchedule->end_date_time ? Carbon::parse($checkSchedule->end_date_time) : null;
        if ($startDateTime && isset($int)) {
            // Add the interval to the parsed time
            $startDateTime->addHours($int);
        }

        if ($startDateTime2 && isset($int)) {
            // Add the interval to the parsed time
            $startDateTime2->addHours($int);
        }

        $fromDate = $startDateTime ? $startDateTime->format('h:i A') : null;
        $toDate = $startDateTime2 ? $startDateTime2->format('h:i A') : null;

        return view('tickets.iframe_job_show', ['Payment' => $Payment, 'jobservice' => $jobservice, 'jobproduct' => $jobproduct, 'jobFields' => $jobFields, 'ticket' => $ticket, 'Sitetagnames' => $Sitetagnames, 'technicians' => $technicians, 'techniciansnotes' => $techniciansnotes, 'customer_tag' => $customer_tag, 'job_tag' => $job_tag, 'jobtagnames' => $jobtagnames, 'leadsource' => $leadsource, 'source' => $source, 'activity' => $activity, 'files' => $files, 'schedule' => $schedule, 'jobTimings' => $jobTimings, 'travelTime' => $travelTime, 'checkSchedule' => $checkSchedule, 'assignedJobs' => $assignedJobs, 'job_appliance' => $job_appliance, 'appliances' => $appliances, 'manufacturers' => $manufacturers, 'timeIntervals' => $timeIntervals, 'dateTime' => $dateTime, 'date' => $date, 'fromDate' => $fromDate, 'toDate' => $toDate]);
    }
    public function indexiframe()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 32;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $servicearea = LocationServiceArea::all();
        $manufacturer = Manufacturer::all();
        $technicianrole = User::where('role', 'technician')->where('status', 'active')->get();
        $totalCalls = JobModel::count();
        $inProgress = JobModel::where('status', 'in_progress')->count();
        $opened = JobModel::where('status', 'open')->count();
        $complete = JobModel::where('status', 'closed')->count();
        $status = JobModel::all();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $technicians = JobModel::with('jobdetailsinfo', 'jobassignname')->orderBy('created_at', 'desc')->get();
        return view('tickets.iframe_job_index', [
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

    public function updatejob(Request $request)
    {

        $technician = JobModel::where('id', $request->jobId)->first();
        ;

        if (!$technician) {
            return response()->json(['success' => false, 'message' => 'Job not found.'], 404);
        }

        // Update the job fields
        $technician->is_confirmed = $request->job_confirmed;
        $technician->is_published = $request->is_published;
        $technician->status = $request->job_closed;
        $technician->save();

        // Update the schedule
        $schedule = Schedule::where('job_id', $request->jobId)->first();

        if ($schedule) {
            $schedule->show_on_schedule = $request->job_schedule;
            $schedule->save();
        } else {
            return response()->json(['success' => false, 'message' => 'Schedule not found for the job.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
    }

    public function flagCustomer(Request $request)
    {


        $jobNote = new JobNoteModel([
            'user_id' => $request->technician_id,
            'job_id' => $request->job_id,
            'note' => $request->flag_reason,
            'added_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'is_flagged' => 'yes',
        ]);

        $jobNote->save();

        $user = User::where('id', $request->customer_id)->first();
        $user->flag_id = $request->flag_id;
        $user->save();

        $techniciansnotes = JobNoteModel::where('job_id', '=', $request->job_id)
            ->Leftjoin('users', 'users.id', '=', 'job_notes.added_by')
            ->select('job_notes.*', 'users.name', 'users.user_image')
            ->get();

        return response()->json([
            'success' => true,
            'techniciansnotes' => $techniciansnotes,
            'message' => 'Flag added successfully!'
        ]);
    }


}
