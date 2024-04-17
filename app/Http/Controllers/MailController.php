<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use Mail;
use App\Mail\ResheduleTechnician;
use App\Mail\ResheduleCustomer;
use Illuminate\Support\Facades\Mail as FacadesMail;

class MailController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            // Find the schedule with related models
            $schedule = Schedule::with('jobModel', 'technician')->findOrFail($request->schedule_id);
    
            // Retrieve technician details
            $technician = $schedule->technician;
    
            // Retrieve customer details
            $customer_id = $schedule->jobModel->customer_id;

            $customer = User::where('id', $customer_id)->first();
            // Prepare data for email
            $maildata = [$technician, $customer,$schedule]; 
    
            // Recipient email addresses
            $recipients = ['bawanesumit01@gmail.com'];
            $recipients2 = ['bawanesumit01@gmail.com'];
    
            // Send email
            Mail::to($recipients)->send(new ResheduleTechnician($maildata));
            Mail::to($recipients2)->send(new ResheduleCustomer($maildata));
    
            // If execution reaches here, email was sent successfully
            $message = 'Email sent successfully';
    
            // Return success response
            return response()->json(['message' => $message], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the schedule or customer with the given ID is not found
            return response()->json(['error' => 'Schedule or customer not found'. $e], 404);
        } catch (\Exception $e) {
            // Handle other exceptions, such as mail configuration issues
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
