<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use App\Models\LayoutDash;
use App\Models\LayoutDashModule;
use App\Models\LayoutDashModuleList;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LayoutDashController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $Id = $request->id;
            $layout = LayoutDash::where('id', $Id)->first();
        } else {
            $Id = auth()->user()->id;
            $user = User::where('id', $Id)->first();
            $layout = LayoutDash::where('id', $user->layout_id)->first();
        }

        $timezone_name = Session::get('timezone_name');

        if (!$layout) {
            return view('404');
        }

        $allVariable = LayoutDashModule::where('layout_id', $layout->id)->get();
        $moduleIds = $allVariable->pluck('module_id')->toArray();
        $List = LayoutDashModuleList::whereNotIn('module_id', $moduleIds)->get();


        $variable = LayoutDashModule::with('ModuleList')->where('layout_id', $layout->id)->where('is_active', 'no')->get();

        $cardPositions = LayoutDashModule::where('layout_id', $layout->id)->where('is_active', 'yes')->orderBy('position')->get();

        $layoutList = LayoutDash::all();

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


        return view('dashboard.index', compact('variable', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount', 'layout', 'layoutList', 'activity', 'userNotifications', 'technicianuser', 'customeruser', 'List'));
    }
    public function savePositions(Request $request)
    {
        $positions = json_decode($request->positions, true);
        $userId = auth()->user()->id;

        \DB::transaction(function () use ($positions, $request, $userId) {
            foreach ($positions as $position) {
                LayoutDashModule::updateOrCreate(
                    [
                        'layout_id' => $request->layout_id,
                        'module_id' => $position['module_id']
                    ],
                    [
                        'position' => $position['position'],
                        'updated_by' => $userId
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Positions saved successfully!');
    }

    public function updateStatus(Request $request)
    {

        $section = LayoutDashModule::where('layout_id', $request->layout_id)->where('module_id', $request->module_id)->first();

        if ($section) {
            $section->is_active = 'yes';
            $section->save();
        } else {
            $addSection = new LayoutDashModule();
            $addSection->layout_id = $request->layout_id;
            $addSection->module_id = $request->module_id;
            $addSection->updated_by = auth()->user()->id;
            $addSection->is_active = 'yes';

            $addSection->save();
        }


        return redirect()->back()->with('success', 'Section status updated successfully.');
    }
    
    public function changeStatus(Request $request)
    {
        $elementId = $request->input('module_id');

        // Assuming you have a model named CardPosition
        $cardPosition = LayoutDashModule::where('layout_id',$request->layout_id)->where('module_id', $elementId)->first();
        if ($cardPosition) {
            $cardPosition->is_active = 'no'; // or true based on your requirement
            $cardPosition->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Module not found']);
    }

    public function updateLayoutName(Request $request, $id)
    {
        // Validate request

        // Find layout by ID
        $layout = LayoutDash::findOrFail($id);

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
        $layout = new LayoutDash();

        // Update layout name
        $layout->layout_name = $request->layout_name;
        $layout->added_by = auth()->user()->id;
        $layout->updated_by = auth()->user()->id;
        $layout->save();

        // Redirect back with success message or any other response
        return redirect()->back()->with('success', 'Layout added successfully.');
    }

    public function createNewLayout(Request $request)
    {
        // Retrieve the layout name from the request
        $layoutName = $request->input('layout_name');

        // Create and save the new layout
        $layout = new LayoutDash();
        $layout->layout_name = $layoutName;
        $layout->added_by = auth()->user()->id;
        $layout->updated_by = auth()->user()->id;
        $layout->save();

        // Retrieve the existing card positions
        $cardPositions = LayoutDashModule::where('layout_id', 1)->get();

        // Iterate over each card position and update the layout_id field
        foreach ($cardPositions as $cardPosition) {
           $newMOdule = new LayoutDashModule();

           $newMOdule->layout_id = $layout->id;
           $newMOdule->module_id = $cardPosition->module_id;
           $newMOdule->position = $cardPosition->position;
           $newMOdule->is_active = $cardPosition->is_active;
           $newMOdule->updated_by = auth()->user()->id;

           $newMOdule->save();
        }

        // Redirect or return a response
        return redirect()->back()->with('success', 'Layout saved successfully!');
    }
}
