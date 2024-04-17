<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use Mail;
use App\Mail\ResheduleTechnician;
use Illuminate\Support\Facades\Mail as FacadesMail;

class MailController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $schedule = Schedule::with('JobModel', 'technician')
                ->findOrFail($request->schedule_id);

            $technician = $schedule->technician;

            $customer_id = $schedule->JobModel->customer_id;

            $customer = User::findOrFail($customer_id);

            $maildata = ''; 

            $recipients = ['bawanesumit01@gmail.com'];

            // Send email
            $mailSent = Mail::to($recipients)->send(new ResheduleTechnician($maildata));

            // Check if the email was sent successfully
            if (!$mailSent) {
                $message = 'Failed to send email';
            } else {
                $message = 'Email sent successfully';
            }

            // You can return the message or use it as needed
            return response()->json(['message' => $message], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the schedule or customer with the given ID is not found
            return response()->json(['error' => 'Schedule or customer not found'], 404);
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
