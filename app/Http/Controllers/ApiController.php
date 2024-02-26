<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\JobModel;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{
  
   public function user_login(Request $request)
{
    // Find user by email
    $user = User::where('email', '=', $request->email)->first();

    // Check if user exists
    if ($user) {
        // Check if password matches
        if (Hash::check($request->password, $user->password)) {
            // Check if user's role is "technician"
            if ($user->role === 'technician') {
                return response()->json(['status' => true, 'data' => $user, 'message' => 'Login Successful']);
            } else {
                return response()->json(['status' => false, 'message' => 'Unauthorized access']);
            }
        } else {
            // Password does not match
            return response()->json(['status' => false, 'message' => 'Invalid email or password']);
        }
    } else {
        // User not found
        return response()->json(['status' => false, 'message' => 'Invalid email or password']);
    }
}

public function reset_password(Request $request)
    {
        $request->validate([
           // 'email' => 'required|email|exists:users,email',
        ]);

      $user = User::where('email', $request->email)
            ->where('role', 'technician')
            ->first();


        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $newPassword = Str::random(10);

        $user->password = Hash::make($newPassword);
        $user->save();

        $companyName = "Frontier"; 

       
        Mail::send('emailtouser.forget_password_email', ['newPassword' => $newPassword, 'companyName' => $companyName], function ($message) use ($request) {
            $message->to($request->email)->subject('Updated Password');
            $message->from('yashdhokane890@gmail.com', 'Admin');
        });

        return response()->json(['message' => 'Password reset successful. Check your email for the new password.'], 200);
    }


 public function getTechnicianJobs(Request $request)
    {
        $userId = $request->input('user_id');
        $currentDate = Carbon::now()->toDateString();
       // dd($currentDate);
        // Check if there are any jobs for the specified technician (user_id) with the current date
        $jobs = JobModel::where('technician_id', $userId)
                    ->whereDate('created_at', $currentDate)
                    
                    ->get();

        if ($jobs->isEmpty()) {
            return response()->json(['message' => 'Today no  jobs are available '], 404);
        }

        return response()->json($jobs);
    }


public function getTechnicianJobsHistory(Request $request)
{
    $userId = $request->input('user_id');
    $date = $request->input('date');

    // Check if the date is provided
    if (!$date) {
        // If the date is missing, return a 400 Bad Request response
        return response()->json(['message' => 'Date is required'], 400);
    }

    // Parse and format the date using Carbon
    $formattedDate = Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');

    // Filter the jobs by technician_id and the specified date
    $jobs = JobModel::where('technician_id', $userId)
                ->whereDate('created_at', $formattedDate)
                ->get();

    if ($jobs->isEmpty()) {
        return response()->json(['message' => 'No jobs available for the specified date'], 404);
    }

    return response()->json($jobs);
}

}
