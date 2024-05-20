<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\JobModel;
use App\Models\CustomerData;
use Illuminate\Http\Request;
use App\Models\CustomerFiles;
use App\Models\CustomerDataJob;
use App\Models\CustomerDataNotes;
use App\Models\CustomerDataServices;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;



class CustomerDataController extends Controller
{
    public function index($status = null)
    {
        // Start with the base query for users with eager loading
        $usersQuery = User::with('customerdatafetch'); // Eager load the related data

        // Optional: Filter by user role
        $usersQuery->where('role', 'customer'); // Or any other role if needed

        // Optional: Filter by status
        if ($status === 'deactive') {
            $usersQuery->where('status', 'deactive');
        } else {
            $usersQuery->where('status', 'active');
        }

        // Optional: Order by related model's field
        $usersQuery->Leftjoin('customer_data', 'users.id', '=', 'customer_data.user_id')
            ->orderBy('customer_data.updated_at', 'desc'); // Order by the related model's column

        // Fetch all users with pagination
        $users = $usersQuery->paginate(50); // You can adjust pagination size as needed

        // Return the view with user data
        return view('customerdata.index', ['users' => $users]);
    }

    // public function searchingcustomerdata(Request $request)
    // {
    //     $query = $request->input('search');

    //     $users = CustomerData::with('userdata1', 'Jobdata')
    //         ->where(function ($queryBuilder) use ($query) {
    //             $queryBuilder->where('name', 'like', '%' . $query . '%')
    //                 ->orWhere('email', 'like', '%' . $query . '%');
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(50);

    //     $tbodyHtml = view('customerdata.searchcustomerdata', compact('users'))->render();

    //     return response()->json(['tbody' => $tbodyHtml]);
    // }

    public function checkAndUpdateAndView(Request $request)
    {
        $userId = $request->input('user_id');

        // Check if user exists in customer_data table
        $customerData = CustomerData::where('user_id', $userId)->first();

        if (!$customerData) {
            // Insert a new record if user not present
            CustomerData::create([
                'user_id' => $userId,
                'no_of_visits' => 0,
                'job_completed' => 0,
                'tcc' => '',
                'issue_resolved' => '',
                'admin_comment' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Return the URL to redirect to
        return redirect()->route('customersdata.show', $userId);
    }

    public function show($id)
    {
        $username = User::find($id);
        // $users = CustomerData::where('user_id', $id)->get();
        // dd($users);
        $ticketNumbers = CustomerDataJob::where('user_id', $id)->pluck('ticket_number')->toArray();
        $tccValues = CustomerDataJob::where('user_id', $id)->pluck('tcc')->toArray();

        $users = CustomerData::with('userdata', 'Jobdata', 'Jobdata.Customerservice', 'Jobdata.Customernote')->where('user_id', $id)->first();

        //dd($users);
        // $Job = JobModel::with('schedule', 'technician', 'Customerservice', 'Customerdata', 'Customernote')->where('customer_id', $user->user_id)->get();
        // $visitcount = JobModel::where('customer_id', $user->user_id)->where('status', 'open')->count();
        // // dd($visitcount);
        // $jobcompletecount = JobModel::where('customer_id', $user->user_id)->where('status', 'closed')->count();

        return view('customerdata.show', compact('users', 'ticketNumbers', 'tccValues', 'username'));
    }
    // public function update(Request $request)
    // {
    //     dd($request->all());
    //     $jobCode = $request->input('ticket_number');
    //     $tcc = $request->input('tcc');
    //     $scheduleDate = $request->input('schedule_date');
    //     $technicianName = $request->input('technician');
    //     $service_name = $request->input('service_name');
    //     $amount = $request->input('amount');
    //     $notes_by = $request->input('notes_by');
    //     $notes = $request->input('notes');
    //     $jobId = $request->input('job_id');
    //     $no_of_visits = $request->input('no_of_visits');
    //     $job_completed = $request->input('job_completed');
    //     $issue_resolved = $request->input('issue_resolved');

    //     $job = CustomerDataJob::find($jobId);
    //     if (!$job) {
    //         return redirect()->back()->with('error', 'Service not found, Please add new job ..');
    //     }

    //     if ($jobCode !== null) {
    //         $job->ticket_number = $jobCode;
    //     }
    //     if ($scheduleDate !== null) {
    //         $job->schedule_date = $scheduleDate;
    //     }

    //     if ($tcc !== null) {
    //         $job->tcc = $tcc;
    //     }

    //     if ($technicianName !== null) {
    //         $job->technician = $technicianName;
    //         $job->save();
    //     }

    //     $job->save();

    //     $customerData = CustomerData::updateOrCreate(['user_id' => $job->user_id], []);
    //     $customerData->updated_at = now();


    //     if ($no_of_visits !== null) {
    //         $customerData->no_of_visits = $no_of_visits;
    //     }
    //     if ($job_completed !== null) {
    //         $customerData->job_completed = $job_completed;
    //     }
    //     if ($issue_resolved !== null) {
    //         $customerData->issue_resolved = $issue_resolved;
    //     }
    //     $customerData->save();

    //     // $service = CustomerDataServices::updateOrCreate(['job_id' => $job->job_id], []);
    //     // if ($service_name !== null) {
    //     //     $service->service_name = $service_name;
    //     // }
    //     // if ($amount !== null) {
    //     //     $service->amount = $amount;
    //     // }
    //     // $service->save();

    //     // $note = CustomerDataNotes::updateOrCreate(['job_id' => $job->job_id], []);
    //     // if ($notes_by !== null) {
    //     //     $note->notes_by = $notes_by;
    //     // }
    //     // if ($notes !== null) {
    //     //     $note->notes = $notes;
    //     //     $note->save();
    //     // }
    //     $ticketNumbers = CustomerDataJob::pluck('ticket_number')->toArray();
    //     $tccValues = CustomerDataJob::pluck('tcc')->toArray();
    //     $user_id = $request->input('user_id');
    //     // Convert array values to comma-separated strings
    //     $ticketNumbersString = implode(',', $ticketNumbers);
    //     $tccValuesString = implode(',', $tccValues);

    //     $customerData = CustomerData::updateOrCreate(['user_id' => $job->user_id], []);
    //     // $customerData->user_id = $job->user_id;

    //     $customerData->ticket_number = $ticketNumbersString;
    //     $customerData->tcc = $tccValuesString;
    //     $customerData->save();


    //     if (!empty($request->file('files'))) {
    //         foreach ($request->file('files') as $file) {
    //             // Generate a unique filename
    //             $imgname = 'e' . rand(000, 999) . time() . '.' . $file->getClientOriginalExtension();

    //             // Directory path for user's images
    //             $directoryPath = public_path('images/users/' . $job->user_id);

    //             // Ensure the directory exists; if not, create it
    //             if (!File::exists($directoryPath)) {
    //                 File::makeDirectory($directoryPath, 0777, true);
    //             }

    //             // Move the file to the user's directory
    //             $file->move($directoryPath, $imgname);

    //             // Create a new entry in the database for the image
    //             $customerFile = new CustomerFiles();
    //             $customerFile->user_id = $job->user_id;
    //             $customerFile->filename = $imgname;
    //             $customerFile->path = 'images/users/' . $job->user_id; // Store relative path
    //             $customerFile->type = $file->getClientMimeType();
    //             $customerFile->storage_location = 'local'; // Assuming storage location is local
    //             $customerFile->save();
    //         }

    //         foreach ($service_name as $key => $service) {
    //             // Check if service name and amount are not null before creating or updating the record
    //             if (!empty($service) && isset($amount[$key])) {
    //                 CustomerDataServices::updateOrCreate(
    //                     ['job_id' => $job->job_id, 'service_name' => $service],
    //                     ['amount' => $amount[$key]]
    //                 );
    //             }
    //         }




    //         // Create CustomerDataNotes records
    //         foreach ($notes as $key => $note) {
    //             // Check if notes and notes_by are not null before creating or updating the record
    //             if (!empty($note) && isset($notes_by[$key])) {
    //                 CustomerDataNotes::updateOrCreate(
    //                     ['job_id' => $job->job_id, 'notes_by' => $notes_by[$key]],
    //                     ['notes' => $note]
    //                 );
    //             }
    //         }
    //         //  dd(1);
    //         return redirect()->back()->with('success', 'Job updated successfully.');
    //     }
    // }

     public function update(Request $request)
    {
        //  dd($request->all());
        // Validate the incoming request data
        $request->validate([
            // 'ticket_number.*' => 'required|string',
            // 'job_id.*' => 'required|integer',
            // 'tcc.*' => 'nullable|string',
            // 'schedule_date.*' => 'nullable|string',
            // 'technician.*' => 'nullable|string',
            // 'service_name.*' => 'nullable|string',
            // 'amount.*' => 'nullable|numeric',
            // 'notes.*' => 'nullable|string',
            // 'notes_by.*' => 'nullable|string',
            // 'no_of_visits' => 'nullable|integer',
            // 'job_completed' => 'nullable|integer',
            // 'issue_resolved' => 'nullable|string',
        ]);

        $jobIds = $request->input('job_id');
        $ticketNumbers = $request->input('ticket_number');
        $tccValues = $request->input('tcc');
        $scheduleDates = $request->input('schedule_date');
        $technicianNames = $request->input('technician');
        $serviceNames = $request->input('service_name');
        $amounts = $request->input('amount');
        $notesBy = $request->input('notes_by');
        $notes = $request->input('notes');
        $no_of_visits = $request->input('no_of_visits');
        $job_completed = $request->input('job_completed');
        $issue_resolved = $request->input('issue_resolved');

        // dd($noOfVisits);
        foreach ($jobIds as $key => $jobId) {
            $job = CustomerDataJob::find($jobId);
            if (!$job) {
                return redirect()->back()->with('error', 'Service not found, Please add new job ..');
            }

            // Update job fields
            $job->ticket_number = $ticketNumbers[$key] ?? $job->ticket_number;
            $job->schedule_date = $scheduleDates[$key] ?? $job->schedule_date;
            $job->tcc = $tccValues[$key] ?? $job->tcc;
            $job->technician = $technicianNames[$key] ?? $job->technician;
            $job->save();

            $customerData = CustomerData::updateOrCreate(['user_id' => $job->user_id], []);
            $customerData->updated_at = now();

            if ($no_of_visits !== null) {
                $customerData->no_of_visits = $no_of_visits;
            }
            if ($job_completed !== null) {
                $customerData->job_completed = $job_completed;
            }
            if ($issue_resolved !== null) {
                $customerData->issue_resolved = $issue_resolved;
            }
            $customerData->save();

            // Update or create service records
            foreach ($serviceNames[$key] as $serviceIndex => $serviceName) {
                CustomerDataServices::updateOrCreate(
                    ['job_id' => $jobId, 'service_name' => $serviceName],
                    ['amount' => $amounts[$key][$serviceIndex] ?? 0]
                );
            }

            // Update or create note records
            foreach ($notes[$key] as $noteIndex => $note) {
                CustomerDataNotes::updateOrCreate(
                    ['job_id' => $jobId, 'notes_by' => $notesBy[$key][$noteIndex]],
                    ['notes' => $note]
                );
            }



        }

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate a unique filename
                $imgname = 'e' . rand(000, 999) . time() . '.' . $file->getClientOriginalExtension();

                // Directory path for user's images
                $directoryPath = public_path('images/users/' . $job->user_id);

                // Ensure the directory exists; if not, create it
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true);
                }

                // Move the file to the user's directory
                $file->move($directoryPath, $imgname);

                // Create a new entry in the database for the image
                $customerFile = new CustomerFiles();
                $customerFile->user_id = $job->user_id;
                $customerFile->filename = $imgname;
                $customerFile->path = 'images/users/' . $job->user_id . '/' . $imgname; // Store full relative path
                $customerFile->type = $file->getClientMimeType();
                $customerFile->storage_location = 'local'; // Assuming storage location is local
                $customerFile->save();
            }
        }

        return redirect()->back()->with('success', 'Job updated successfully.');
    }

  

    public function store(Request $request)
    {
        // dd($request->all());

        $jobCode = $request->input('ticket_number');
        $tcc = $request->input('tcc');
        $scheduleDate = $request->input('schedule_date');
        $technicianName = $request->input('technician');
        $service_name = $request->input('service_name');
        $amount = $request->input('amount');
        $notes_by = $request->input('notes_by');
        $notes = $request->input('notes');
        // $no_of_visits = $request->input('no_of_visits');
        // $job_completed = $request->input('job_completed');
        // $issue_resolved = $request->input('issue_resolved');
        $status = $request->input('status');
        $time_travel = $request->input('time_travel');
        $time_on_job = $request->input('time_on_job');
        $total_time = $request->input('total_time');
        $user_id = $request->input('user_id');

        //  $carbonScheduleDate = Carbon::parse($scheduleDate);

        // Format the Carbon instance as a string
        //  $formattedScheduleDate = $carbonScheduleDate->format('D, M j \'y h:i a T');
        // Create a new CustomerDataJob record
        $job = new CustomerDataJob();

        if ($user_id !== null) {

            $job->user_id = $user_id;
        }
        if ($jobCode !== null) {

            $job->ticket_number = $jobCode;
        }
        if ($scheduleDate !== null) {

            $job->schedule_date = $scheduleDate;
        }
        if ($tcc !== null) {

            $job->tcc = $tcc;
        }
        $job->technician = $technicianName;
        if ($status !== null) {
            $job->status = $status;
        }
        if ($time_travel !== null) {
            $job->time_travel = $time_travel;
        }
        if ($time_on_job !== null) {
            $job->time_on_job = $time_on_job;
        }
        if ($total_time !== null) {
            $job->total_time = $total_time;
        }
        $job->save();

        // Create a new CustomerData record
        // $customerData = new CustomerData();

        // $customerData->user_id = $user_id; // Assuming user_id is generated during job creation
        // if ($no_of_visits !== null) {
        //     $customerData->no_of_visits = $no_of_visits;
        // }
        // if ($job_completed !== null) {
        //     $customerData->job_completed = $job_completed;
        // }
        // if ($issue_resolved !== null) {
        //     $customerData->issue_resolved = $issue_resolved;
        // }
        // $customerData->save();
        $ticketNumbers = CustomerDataJob::pluck('ticket_number')->toArray();
        $tccValues = CustomerDataJob::pluck('tcc')->toArray();
        $user_id = $request->input('user_id');
        // Convert array values to comma-separated strings
        $ticketNumbersString = implode(',', $ticketNumbers);
        $tccValuesString = implode(',', $tccValues);

        $customerData = CustomerData::updateOrCreate(['user_id' => $user_id], []);
        $customerData->updated_at = now();
        $customerData->created_at = now();

        $customerData->ticket_number = $ticketNumbersString;
        $customerData->tcc = $tccValuesString;
        $customerData->save();

        // Create a new CustomerDataServices record
        // $service = new CustomerDataServices();
        // $service->job_id = $job->job_id; // Assuming job_id is generated during job creation
        // if ($service_name !== null) {
        //     $service->service_name = $service_name;
        // }
        // if ($amount !== null) {
        //     $service->amount = $amount;
        // }
        // $service->save();

        // // Create a new CustomerDataNotes record
        // $note = new CustomerDataNotes();
        // $note->job_id = $job->job_id; // Assuming job_id is generated during job creation
        // if ($notes_by !== null) {
        //     $note->notes_by = $notes_by;
        // }
        // if ($notes !== null) {
        //     $note->notes = $notes;
        // }
        // $note->save();

        foreach ($service_name as $key => $service) {
            // Check if service name and amount are not null before creating the record
            if (!empty($service) && isset($amount[$key])) {
                $serviceRecord = new CustomerDataServices();
                $serviceRecord->job_id = $job->job_id; // Assuming job_id is generated during job creation
                $serviceRecord->service_name = $service;
                $serviceRecord->amount = $amount[$key];
                $serviceRecord->save();
            }
        }

        // Iterate over each image
        // if (!empty($request->filename)) {
        //     foreach ($request->filename as $key => $image) {
        //         // Extract image extension
        //         $extension = explode('/', mime_content_type($image))[1];

        //         // Decode base64 image data
        //         $data = base64_decode(substr($image, strpos($image, ',') + 1));

        //         // Generate a unique filename
        //         $imgname = 'e' . rand(000, 999) . $key . time() . '.' . $extension;

        //         // Directory path for user's images
        //         $directoryPath = public_path('images/users/' . $job->user_id);

        //         // Ensure the directory exists; if not, create it
        //         if (!File::exists($directoryPath)) {
        //             File::makeDirectory($directoryPath, 0777, true);
        //         }

        //         // Save the image to the user's directory
        //         if (file_put_contents($directoryPath . '/' . $imgname, $data)) {
        //             // Create a new entry in the database for the image
        //             $file = new CustomerFiles();
        //             $file->user_id = $job->user_id;
        //             $file->filename = $imgname;
        //             $file->path = $directoryPath;
        //             $file->type = 'image/' . $extension;
        //             $file->storage_location = 'local'; // Assuming storage location is local
        //             $file->save();
        //         }
        //     }
        //  dd(1);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate a unique filename
                $imgname = 'e' . rand(000, 999) . time() . '.' . $file->getClientOriginalExtension();

                // Directory path for user's images
                $directoryPath = public_path('images/users/' . $job->user_id);

                // Ensure the directory exists; if not, create it
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true);
                }

                // Move the file to the user's directory
                $file->move($directoryPath, $imgname);

                // Create a new entry in the database for the image
                $customerFile = new CustomerFiles();
                $customerFile->user_id = $job->user_id;
                $customerFile->filename = $imgname;
                $customerFile->path = 'images/users/' . $job->user_id . '/' . $imgname; // Store full relative path
                $customerFile->type = $file->getClientMimeType();
                $customerFile->storage_location = 'local'; // Assuming storage location is local
                $customerFile->save();
            }




            // Create CustomerDataNotes records
            foreach ($notes as $key => $note) {
                // Check if notes and notes_by are not null before creating the record
                if (!empty($note) && isset($notes_by[$key])) {
                    $noteRecord = new CustomerDataNotes();
                    $noteRecord->job_id = $job->job_id; // Assuming job_id is generated during job creation
                    $noteRecord->notes_by = $notes_by[$key];
                    $noteRecord->notes = $note;
                    $noteRecord->save();
                }
            }

            return redirect()->back()->with('success', 'Job stored successfully.');
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $users = User::with('customerdatafetch')->where('role', 'customer')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(50);

        $tbodyHtml = view('customerdata.searchcustomerdata', compact('users'))->render();

        return response()->json(['tbody' => $tbodyHtml]);
    }
}
