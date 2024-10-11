<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\CustomerUserAddress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

// use Google\Service\AnalyticsReporting\User;

class AdminProfileController extends Controller
{



    public function email_verified(Request $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);

        if ($user && $user->id == $userId) {
            // Check if email_notifications is already set to 1
            if ($user->email_verified == 1) {
                $user->email_verified = 0; // Set to 0
            } else {
                $user->email_verified = 1; // Set to 1
            }

            $user->save();

            return redirect()->back()->with('success', 'Email verification preference has been updated.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
    }

    public function sms(Request $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);

        if ($user && $user->id == $userId) {
            // Check if email_notifications is already set to 1
            if ($user->sms_notification == 1) {
                $user->sms_notification = 0; // Set to 0
            } else {
                $user->sms_notification = 1; // Set to 1
            }

            $user->save();

            return redirect()->back()->with('success', 'SMS notification preference has been updated.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
    }

    public function email(Request $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);

        if ($user && $user->id == $userId) {
            // Check if email_notifications is already set to 1
            if ($user->email_notifications == 1) {
                $user->email_notifications = 0; // Set to 0
            } else {
                $user->email_notifications = 1; // Set to 1
            }

            $user->save();

            return redirect()->back()->with('success', 'Email notification preference has been updated.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
    }
    public function index()
    {
        $userId = auth()->id(); // Retrieve the authenticated user's ID

        // Find the user and related data using the authenticated user's ID
        $user = User::find($userId);
        $userAddress = CustomerUserAddress::where('user_id', $userId)->first();

        $locationStates = LocationState::all();
        $meta = $user->meta;

        // Retrieve user meta information
        $first_name = $user->meta()->where('meta_key', 'first_name')->first()->meta_value ?? '';
        $last_name = $user->meta()->where('meta_key', 'last_name')->first()->meta_value ?? '';
        $home_phone = $user->meta()->where('meta_key', 'home_phone')->first()->meta_value ?? '';
        $work_phone = $user->meta()->where('meta_key', 'work_phone')->first()->meta_value ?? '';
        $ssn = $user->meta()->where('meta_key', 'ssn')->first()->meta_value ?? '';
        $dob = $user->meta()->where('meta_key', 'dob')->first()->meta_value ?? '';

        return view('adminprofile.myprofile', compact('dob', 'ssn', 'work_phone', 'home_phone', 'last_name', 'first_name', 'locationStates', 'user', 'userAddress'));
    }

 public function activity(Request $request)
    {
        $userId = Auth::id();

        $jobActivity = DB::table('job_activity')
            ->select(
                'job_activity.user_id',
                'job_activity.activity',
                'job_activity.created_at as activity_date',
                DB::raw("'job_activity' as activity_type"),
                'users.*'
            )
            ->join('users', 'job_activity.user_id', '=', 'users.id')
            ->where('job_activity.user_id', $userId);

        $userActivity = DB::table('user_activity')
            ->select(
                'user_activity.user_id',
                'user_activity.activity',
                'user_activity.created_at as activity_date',
                DB::raw("'user_activity' as activity_type"),
                'users.*'
            )
            ->join('users', 'user_activity.user_id', '=', 'users.id')
            ->where('user_activity.user_id', $userId);

        $activity = $jobActivity->union($userActivity)
            ->orderBy('activity_date', 'desc')
            ->paginate(50);

        $userNotifications = UserNotification::with('notice')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate(50);

        if ($request->ajax()) {
            return response()->json([
                'data' => [
                    'activities' => $activity->items(),
                    'notifications' => $userNotifications->items()
                ],
                'next_activity_page' => $activity->nextPageUrl(),
                'next_notifications_page' => $userNotifications->nextPageUrl()
            ]);
        }

        return view('adminprofile.myprofile_activity', compact('activity', 'userNotifications'));
    }

    public function loadMoreActivities(Request $request)
    {
        $userId = Auth::id();

        $jobActivity = DB::table('job_activity')
            ->select(
                'job_activity.user_id',
                'job_activity.activity',
                'job_activity.created_at as activity_date',
                DB::raw("'job_activity' as activity_type"),
                'users.*'
            )
            ->join('users', 'job_activity.user_id', '=', 'users.id')
            ->where('job_activity.user_id', $userId);

        $userActivity = DB::table('user_activity')
            ->select(
                'user_activity.user_id',
                'user_activity.activity',
                'user_activity.created_at as activity_date',
                DB::raw("'user_activity' as activity_type"),
                'users.*'
            )
            ->join('users', 'user_activity.user_id', '=', 'users.id')
            ->where('user_activity.user_id', $userId);

        $activity = $jobActivity->union($userActivity)
            ->orderBy('activity_date', 'desc')
            ->paginate(50);

        if ($request->ajax()) {
            return response()->json([
                'data' => $activity->items(),
                'next_page_url' => $activity->nextPageUrl()
            ]);
        }
    }

    public function loadMoreNotifications(Request $request)
    {
        $userId = Auth::id();

        $userNotifications = UserNotification::with('notice')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate(50);

        if ($request->ajax()) {
            return response()->json([
                'data' => $userNotifications->items(),
                'next_page_url' => $userNotifications->nextPageUrl()
            ]);
        }
    }







    public function notification()
    {
        $userId = auth()->id();
        $userNotifications = UserNotification::with('notice')->where('user_id', $userId)->orderBy('id', 'desc')->get();
        return view('adminprofile.my_notification', compact('userNotifications'));
    }



    public function account()
    {
        $userId = auth()->id();
        $user = User::find($userId);
        return view('adminprofile.myprofile_account', compact('user'));
    }


    public function store(Request $request)
    {
        $request->validate([
            // 'image' => 'required|mimes:gif,jpg,png,jpeg',
            // 'content' => 'required',
        ]);

        $user = User::findOrFail($request->id);

        // Check if a new image file is uploaded
        if ($request->hasFile('user_image')) {
            $directoryName = $user->id;
            $directoryPath = public_path('images/Uploads/users/' . $directoryName);

            // Create directory if it doesn't exist
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Get the uploaded image file
            $image = $request->file('user_image');
            $imageName = $image->getClientOriginalName();

            // Move the uploaded image to the user's directory
            $image->move($directoryPath, $imageName);

            // Delete the previous image if it exists
            if ($user->user_image) {
                $previousImagePath = public_path('images/Uploads/users/' . $directoryName . '/' . $user->user_image);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            // Update the user's image
            $user->user_image = $imageName;
        }

        // Save the user object
        $user->save();

        return redirect()->back()->with('success', 'Image has been updated successfully.');
    }


    public function passstore(Request $request)
    {
        $request->validate([
            // 'currentpassword' => 'required',
            // 'password' => 'required|min:8', // Adjust the minimum length as needed
            // 'conformpassword' => 'required|same:newpassword',
        ]);

        $user = User::find($request->id);

        // Check if the current password matches the one in the database
        if (!Hash::check($request->currentpassword, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update the user's password
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Password has been updated successfully.');
    }


    public function infoadmin(Request $request)
    {
        //    dd($request->all());
        $request->validate([
            // 'currentpassword' => 'required',
            // 'password' => 'required|min:8', // Adjust the minimum length as needed
            // 'conformpassword' => 'required|same:newpassword',
        ]);

        User::where('id', $request->id)->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,

            ]
        );

        CustomerUserAddress::where('user_id', $request->id)->update(
            [
                'city' => $request->city,
                'state_id' => $request->state_id,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'zipcode' => $request->zipcode,


            ]
        );

        $user = User::find($request->id);
        $user->meta()->updateOrCreate(['meta_key' => 'first_name'], ['meta_value' => $request['first_name']]);
        $user->meta()->updateOrCreate(['meta_key' => 'last_name'], ['meta_value' => $request['last_name']]);
        $user->meta()->updateOrCreate(['meta_key' => 'home_phone'], ['meta_value' => $request['home_phone']]);
        $user->meta()->updateOrCreate(['meta_key' => 'work_phone'], ['meta_value' => $request['work_phone']]);
        $user->meta()->updateOrCreate(['meta_key' => 'ssn'], ['meta_value' => $request['ssn']]);
        $user->meta()->updateOrCreate(['meta_key' => 'dob'], ['meta_value' => $request['dob']]);

        // dd(1);

        return redirect()->back()->with('success', 'Information has been updated successfully.');
    }




}