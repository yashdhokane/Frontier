<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Mail\ScheduleTechnician;
use App\Mail\ScheduleCustomer;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $typeName = $request->type;
    
            // Find the schedule with related models
            $schedule = Schedule::with('jobModel', 'technician')->findOrFail($request->schedule_id);
            
            // Retrieve technician and customer details
            $technician = $schedule->technician;
            $customer = User::find($schedule->jobModel->customer_id);
    
            // Prepare data for the email
            $data = compact('technician', 'customer', 'schedule', 'typeName');
            
            $type1 = "schedule_customer";   // Blade view: 'schedule_customer.blade.php'
            $type2 = "schedule_technician"; // Blade view: 'schedule_technician.blade.php'

            // $to ='bawanesumit01@gmail.com';
            $to ='thesachinraut@gmail.com';
    
            if ($typeName == 'reschedule') {
                // Email details for reschedule
                $subject = "Re-Schedule Job Update";
                $msg = "The job has been rescheduled.";
                $from = "admin@example.com";
            } elseif ($typeName == 'schedule') {
                // Email details for schedule
                $subject = "Schedule Job Update";
                $msg = "A new job has been scheduled.";
                $from = "admin@example.com";
            }
    
            // Call the common function to send the email
            // app('commonFunction')($subject, $msg, $from, $to, $type1, $data);
            // app('commonFunction')($subject, $msg, $from, $to, $type2, $data);

            return response()->json(['message' => 'Emails have been sent successfully.'], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Schedule or customer not found'], 404);
           
        } catch (\Exception $e) {
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
