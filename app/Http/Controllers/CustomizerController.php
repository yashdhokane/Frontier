<?php

namespace App\Http\Controllers;

use App\Models\Customizer;
use App\Models\JobModel;
use App\Models\LayoutCustomizer;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $Id = $request->id;
            $layout = LayoutCustomizer::where('id', $Id)->first();
        } else {
            $Id = auth()->user()->id;
            $user = User::where('id', $Id)->first();
           $layout = LayoutCustomizer::where('id', $user->layout_id)->first();
           
        }

        $timezone_name = Session::get('timezone_name');

        if (!$layout) {
            return view('404');
        }
        $variable = Customizer::where('layout_id', $layout->id)->where('is_active', 'no')->get();
        $cardPositions = Customizer::where('layout_id', $layout->id)->where('is_active', 'yes')->orderBy('position')->get();

        $layoutList = LayoutCustomizer::all();

        $job = Schedule::with('JobModel', 'technician')
            ->where('start_date_time', '>', Carbon::now($timezone_name))
            ->latest()->limit(5)->get();

        $paymentopen = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'paid')->orderBy('id', 'desc')

            ->limit(5)->get();

        $paymentclose = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'unpaid')->orderBy('id', 'desc')

            ->limit(5)->get();


        $adminCount = User::where('role', 'admin')->count();
        $dispatcherCount = User::where('role', 'dispatcher')->count();
        $technicianCount = User::where('role', 'technician')->count();
        $customerCount = User::where('role', 'customer')->count();


        $jobActivity = DB::table('job_activity')
            ->select(
                'job_activity.user_id',
                'job_activity.activity',
                'job_activity.created_at as activity_date',
                DB::raw("'job_activity' as activity_type"),
                'users.*' // Select all columns from the users table
            )
            ->join('users', 'job_activity.user_id', '=', 'users.id')
            ->join('user_login_history', 'job_activity.user_id', '=', 'user_login_history.user_id');
        // Fetch user activities for all users
        $userActivity = DB::table('user_activity')
            ->select(
                'user_activity.user_id',
                'user_activity.activity',
                'user_activity.created_at as activity_date',
                DB::raw("'user_activity' as activity_type"),
                'users.*' // Select all columns from the users table
            )
            ->join('users', 'user_activity.user_id', '=', 'users.id');
        // Combine and order the activities by activity_date
        $activity = $jobActivity->union($userActivity)
            ->orderBy('activity_date', 'desc')->limit(10)
            ->get();

        // Fetch notifications for all users
        $userNotifications = UserNotification::with('notice')
            ->orderBy('id', 'desc')->limit(10)
            ->get();


        $technicianuser = User::where('role', 'technician')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();


        foreach ($technicianuser as $key => $user) {
            $areaName = [];
            if (isset($user->service_areas) && !empty($user->service_areas)) {
                $service_areas = explode(',', $user->service_areas);
                foreach ($service_areas as $key1 => $value1) {
                    $location_service_area = DB::table('location_service_area')->where('area_id', $value1)->first();
                    if (isset($location_service_area->area_name) && !empty($location_service_area->area_name)) {
                        $areaName[] = $location_service_area->area_name;
                    }
                }
                $technicianuser[$key]->area_name = implode(', ', $areaName);

                // Retrieve job completion data for the current technician user
                $totalJobsCount = JobModel::where('technician_id', $user->id)->count();

                $completedJobsCount = JobModel::where('technician_id', $user->id)->where('status', 'closed')->count();
                $completionRate = ($totalJobsCount > 0) ? ($completedJobsCount / $totalJobsCount) * 100 : 0;

                // Add job completion data to the technician user object
                $technicianuser[$key]->completed_jobs_count = $completedJobsCount;
                $technicianuser[$key]->total_jobs_count = $totalJobsCount;
                $technicianuser[$key]->completion_rate = $completionRate;
            }
        }

        $customeruser = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        foreach ($customeruser as $user) {
            $userAddresses = DB::table('user_address')
                ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
                ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
                ->where('user_address.user_id', $user->id)
                ->get();

            $jobs = DB::table('jobs')
                ->where('jobs.customer_id', $user->id)
                ->get();
            $grossTotal = DB::table('payments')
                ->selectRaw('sum(`total`) as grosstotal, `customer_id`')
                ->where('customer_id', $user->id)
                ->groupBy('customer_id')
                ->orderByRaw('grosstotal DESC')
                ->first();

            // Merge user addresses and jobs data into user object
            $user->user_addresses = $userAddresses;
            // dd($user->user_addresses );
            $user->jobs = $jobs;
            $user->gross_total = $grossTotal ? $grossTotal->grosstotal : 0;

        }


        return view('customizer.dashboard', compact('variable', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount', 'layout', 'layoutList', 'activity', 'userNotifications','technicianuser','customeruser'));
    }

    public function savePositions(Request $request)
    {
        $positions = json_decode($request->positions, true);
        $userId = auth()->user()->id;

        foreach ($positions as $position) {
            Customizer::updateOrCreate(
                ['element_id' => $position['element_id']],
                ['position' => $position['position'], 'updated_by' => $userId]
            );
        }

        return redirect()->back()->with('success', 'Positions saved successfully!');
    }


    public function updateStatus(Request $request)
    {
        if ($request->has('element_id')) {
            $section = Customizer::where('element_id', $request->element_id)->first();
            $section->is_active = 'no';
        } else {
            $section = Customizer::findOrFail($request->status);
            $section->is_active = 'yes';
        }
        $section->save();

        return redirect()->back()->with('success', 'Section status updated successfully.');
    }

    public function changeStatus(Request $request)
    {
        $elementId = $request->input('element_id');

        // Assuming you have a model named CardPosition
        $cardPosition = Customizer::where('element_id', $elementId)->first();
        if ($cardPosition) {
            $cardPosition->is_active = 'no'; // or true based on your requirement
            $cardPosition->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Element not found']);
    }

    public function updateLayoutName(Request $request, $id)
    {
        // Validate request

        // Find layout by ID
        $layout = LayoutCustomizer::findOrFail($id);

        // Update layout name
        $layout->layout_name = $request->layout_name;
        $layout->updated_by = auth()->user()->id;
        $layout->save();

        // Redirect back with success message or any other response
        return redirect()->back()->with('success', 'Layout name updated successfully.');
    }

    public function createLayout(Request $request)
    {
        // Validate request

        // Find layout by ID
        $layout = new LayoutCustomizer();

        // Update layout name
        $layout->layout_name = $request->layout_name;
        $layout->added_by = auth()->user()->id;
        $layout->updated_by = auth()->user()->id;
        $layout->save();

        // Redirect back with success message or any other response
        return redirect()->back()->with('success', 'Layout added successfully.');
    }
}
