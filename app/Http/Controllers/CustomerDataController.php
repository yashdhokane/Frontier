<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustomerDataServices;
use App\Models\CustomerDataNotes;
use App\Models\JobModel;
use App\Models\CustomerData;
use Illuminate\Http\Request;

class CustomerDataController extends Controller
{
    public function index($status = null)
    {
        // dd($status);
        // $usersQuery = User::where('role', 'customer');

        // if ($status == "deactive") {
        //     $usersQuery->where('status', 'deactive');
        // } else {
        //     $usersQuery->where('status', 'active');
        // }

        // $users = $usersQuery->orderBy('created_at', 'desc')->paginate(50);
        $users = CustomerData::with('userdata1', 'Jobdata')->get();


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


    public function show($id)
    {
        $username = User::find($id);
        // $users = CustomerData::where('user_id', $id)->get();
        // dd($users);

        $users = CustomerData::with('userdata', 'Jobdata', 'Jobdata.schedule', 'Jobdata.technician', 'Jobdata.Customerservice', 'Jobdata.Customernote')->where('user_id', $id)->first();

        //dd($users);
        // $Job = JobModel::with('schedule', 'technician', 'Customerservice', 'Customerdata', 'Customernote')->where('customer_id', $user->user_id)->get();
        // $visitcount = JobModel::where('customer_id', $user->user_id)->where('status', 'open')->count();
        // // dd($visitcount);
        // $jobcompletecount = JobModel::where('customer_id', $user->user_id)->where('status', 'closed')->count();

        return view('customerdata.show', compact('users', 'username'));
    }
    public function update(Request $request)
    {
        // Retrieve data from the form
        // $jobCode = $request->input('job_code');
        $tcc = $request->input('tcc');
        // $scheduleDate = $request->input('schedule_date');
        // $technicianName = $request->input('technician');
        $service_name = $request->input('service_name');
        $amount = $request->input('amount');
        $notes_by = $request->input('notes_by');
        $notes = $request->input('notes');
        $jobId = $request->input('job_id');
        $no_of_visits = $request->input('no_of_visits');
        $job_completed = $request->input('job_completed');
        $issue_resolved = $request->input('issue_resolved');

        // Update the JobModel record with the provided job ID
        $job = JobModel::find($jobId);
        if (!$job) {
            // Handle case where job with given ID is not found
            return redirect()->back()->with('error', 'Job not found.');
        }

        // // Update job fields if they are provided
        // if ($jobCode !== null) {
        //     $job->job_code = $jobCode;
        // }

        // // Save job changes
        // $job->save();

        // Update related CustomerData record if it exists and fields are provided
        $customerData = CustomerData::where('user_id', $job->customer_id)->first();
        if ($customerData && $tcc !== null) {
            $customerData->tcc = $tcc;
            $customerData->no_of_visits = $no_of_visits;
            $customerData->job_completed = $job_completed;
            $customerData->issue_resolved = $issue_resolved;


        }

        // if ($customerData && $scheduleDate !== null) {
        //     $customerData->schedule_date = $scheduleDate;
        // }

        // Save customerData changes
        if ($customerData) {
            $customerData->save();
        }

        // Update related User (Technician) record if it exists and field is provided
        // $technician = User::find($job->technician_id);
        // if ($technician && $technicianName !== null) {
        //     $technician->name = $technicianName;
        //     $technician->save();
        // }

        // Update related CustomerDataServices record if it exists and fields are provided
        $service = CustomerDataServices::where('job_id', $job->id)->first();
        if ($service && $service_name !== null && $amount !== null) {
            $service->service_name = $service_name;
            $service->amount = $amount;
            $service->save();
        }

        // Update related CustomerDataNotes record if it exists and fields are provided
        $note = CustomerDataNotes::where('job_id', $job->id)->first();
        if ($note && $notes_by !== null && $notes !== null) {
            $note->notes_by = $notes_by;
            $note->notes = $notes;
            $note->save();
        }

        // Redirect back to the page with a success message
        return redirect()->back()->with('success', 'Job updated successfully.');
    }




}
