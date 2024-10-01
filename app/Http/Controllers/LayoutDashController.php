<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tool;
use App\Models\UserNotesCustomer;
use App\Models\Role;
use App\Models\SiteLeadSource;
use App\Models\SiteTags;
use App\Models\UserTag;
use App\Models\User;
use App\Models\UsersDetails;
use App\Models\CustomerUserAddress;
use App\Models\LocationState;
use App\Models\Event;
use App\Models\Payment;
use App\Models\JobModel;
use App\Models\Products;
use App\Models\BusinessHours;
use App\Models\Schedule;
use App\Models\JobAssign;
use App\Models\LayoutDash;

use App\Models\FleetVehicle;
use Illuminate\Http\Request;
use App\Models\LayoutDashModule;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Models\LayoutDashModuleList;
use Illuminate\Support\Facades\Session;

class LayoutDashController extends Controller
{

    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');
        $customers = User::where('role', 'customer') // Ensure only users with role 'customer' are selected
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json($customers);
    }

    public function searchTechnicians(Request $request)
    {
        $query = $request->input('query');
        $technicians = User::where('role', 'technician') // Ensure only users with role 'customer' are selected
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json($technicians);
    }

    // public function searchParts(Request $request)
    // {
    //     $query = $request->input('query');
    //     $parts = Products::where('product_name', 'like', "%{$query}%")->limit(5)->get();

    //     return response()->json($parts);
    // }
    public function searchParts(Request $request)
    {
        $query = $request->input('query');

        // Search Products
        $products = Products::where('product_name', 'like', "%{$query}%")
            ->pluck('product_name')
            ->take(5);

        // Search Tools
        $tools = Tool::where('product_name', 'like', "%{$query}%")
            ->pluck('product_name')
            ->take(5);

        // Search Fleet Vehicles
        $fleetVehicles = FleetVehicle::where('vehicle_name', 'like', "%{$query}%")
            ->pluck('vehicle_name')
            ->take(5);

        // Combine results into a single collection and take the first 5 unique items
        $results = $products->merge($tools)->merge($fleetVehicles)->unique()->take(5);

        return response()->json($results);
    }




    public function searchTools(Request $request)
    {
        $query = $request->input('query');
        $tools = Tool::where('product_name', 'like', "%{$query}%")->limit(5)->get();
        return response()->json($tools);
    }
    public function eventsearch(Request $request)
    {
        $query = $request->input('query');

        // Fetch events matching the search query
        $events = Event::where('event_name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json($events);
    }
    public function schedulesearch(Request $request)
    {
        $query = $request->input('query');

        // Fetch schedules matching the query in job_id
        $schedules = Schedule::where('job_id', 'like', '%' . $query . '%')->limit(5)->get();

        // Return the results as JSON
        return response()->json($schedules);
    }
    public function searchPayments(Request $request)
    {
        $query = $request->input('query');

        // Search Payments
        $payments = Payment::where('invoice_number', 'like', "%{$query}%")
            ->limit(5)
            ->get(['invoice_number']);

        return response()->json($payments);
    }

    public function test(Request $request)
    {
        $timezone_name = Session::get('timezone_name');
        // Retrieve the 10 most recent jobs based on 'start_date_time'
        $recentcustomer = JobAssign::with('customer', 'technician')->orderBy('start_date_time', 'desc')
            ->limit(5)
            ->get();
        $schedules1 = Schedule::limit(5)->get();

        $productNames = Products::pluck('product_name')->take(2);
        $toolNames = Tool::pluck('product_name')->take(2);
        $tool = Tool::pluck('product_name')->take(1);

        $fleetVehicleNames = FleetVehicle::pluck('vehicle_name');

        // Merge the collections and take the first 5 unique items
        $product = $productNames->merge($toolNames)->merge($fleetVehicleNames)->unique()->take(5);
        $payments = Payment::limit(5)->get();
        $events = Event::limit(5)->get();
        $job = Schedule::with('JobModel', 'JobTechEvent', 'technician')
            ->where('start_date_time', '>', Carbon::now($timezone_name))
            ->latest()->limit(5)->get();

        $time_interval = Session::get('time_interval');
        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);
        $filterDate = $currentDate->format('Y-m-d');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');

        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        // Query the business hours for the given day
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('D, F j, Y');
        // Fetch active technicians
        $technicians = User::where('role', 'technician')->where('status', 'active')->get();

        // Initialize an empty collection to store schedules
        $schedules = collect();

        // Loop through each technician and fetch their schedules
        foreach ($technicians as $technician) {
            $technicianSchedules = Schedule::where('technician_id', $technician->id)->where('start_date_time', 'LIKE', "%$filterDate%")->get();
            $schedules = $schedules->merge($technicianSchedules);
        }

        $schedules->transform(function ($schedule) use ($time_interval) {
            $schedule->start_date_time = Carbon::parse($schedule->start_date_time)
                ->addHours($time_interval)
                ->format('Y-m-d H:i:s');
            return $schedule;
        });
        $paymentclose = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'unpaid')->orderBy('id', 'desc')

            ->limit(5)->get();

        return view('dashboard.test', compact('recentcustomer', 'product', 'tool', 'events', 'payments', 'schedules1', 'job', 'technicians', 'schedules', 'formattedDate', 'hours', 'tomorrowDate', 'previousDate', 'paymentclose'));
    }

    public function drag(Request $request)
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
        $jobcompleteyes = Schedule::with(['JobModel', 'JobTechEvent', 'technician'])
            //->where('start_date_time', '>', Carbon::now($timezone_name))
            ->whereHas('JobTechEvent', function ($query) {
                $query->where('tech_completed', 'yes');
            })
            // ->latest()
            // ->limit(5)
            ->get();

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


        return view('dashboard.drag', compact('variable', 'jobcompleteyes', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount', 'layout', 'layoutList', 'activity', 'userNotifications', 'technicianuser', 'customeruser', 'List'));
    }

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
        $jobcompleteyes = Schedule::with(['JobModel', 'JobTechEvent', 'technician'])
            //->where('start_date_time', '>', Carbon::now($timezone_name))
            ->whereHas('JobTechEvent', function ($query) {
                $query->where('tech_completed', 'yes');
            })
            // ->latest()
            // ->limit(5)
            ->get();

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
            ->orderBy('activity_date', 'desc')->limit(5)
            ->get();

        // Fetch notifications for all users
        $userNotifications = UserNotification::with('notice')
            ->orderBy('id', 'desc')->limit(5)
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


        return view('dashboard.index', compact('variable', 'jobcompleteyes', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount', 'layout', 'layoutList', 'activity', 'userNotifications', 'technicianuser', 'customeruser', 'List'));
    }
    public function savePositions(Request $request)
    {

        // dd($request->all());
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
        $jobcompleteyes = Schedule::with(['JobModel', 'JobTechEvent', 'technician'])
            //->where('start_date_time', '>', Carbon::now($timezone_name))
            ->whereHas('JobTechEvent', function ($query) {
                $query->where('tech_completed', 'yes');
            })
            // ->latest()
            // ->limit(5)
            ->get();

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



        $section = LayoutDashModule::where('layout_id', $request->layout_id)
            ->where('module_id', $request->module_id)
            ->first();

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

        // Fetch updated card positions
        $cardPosition = LayoutDashModule::with('ModuleList')
            ->where('layout_id', $request->layout_id)
            ->where('module_id', $request->module_id)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Module added successfully.',
            'html' => view('dashboard.card-positions', compact('cardPosition', 'variable', 'jobcompleteyes', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount', 'layout', 'layoutList', 'activity', 'userNotifications', 'technicianuser', 'customeruser', 'List'))->render()
        ]);
    }
    public function changeStatus(Request $request)
    {
        $elementId = $request->input('module_id');

        // Assuming you have a model named CardPosition
        $cardPosition = LayoutDashModule::where('layout_id', $request->layout_id)->where('module_id', $elementId)->first();
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
  return redirect()->route('dash', ['id' => $layout->id])->with('success', 'Layout added successfully.'); 
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


    // app/Http/Controllers/CustomerController.php
    public function fetchData(Request $request)
    {
        $email = $request->query('email');

        // Find the most recent user data based on the email
        $user = User::where('email', $email)->first();

        if ($user) {
            $userDetails = UsersDetails::where('user_id', $user->id)->first();
            $customerAddress = CustomerUserAddress::where('user_id', $user->id)->first();
            $userNotes = UserNotesCustomer::where('user_id', $user->id)->latest()->first();
            $userTags = UserTag::where('user_id', $user->id)->pluck('tag_id')->toArray();

            return response()->json([
                'first_name' => $userDetails->first_name ?? '',
                'last_name' => $userDetails->last_name ?? '',
                'mobile_phone' => $user->mobile ?? '',
                'address1' => $customerAddress->address_line1 ?? '',
                'city' => $customerAddress->city ?? '',
                'state_id' => $customerAddress->state_id ?? '',
                'zip_code' => $customerAddress->zipcode ?? '',
                'home_phone' => $userDetails->home_phone ?? '',
                'work_phone' => $userDetails->work_phone ?? '',
                'company' => $userDetails->customer_company ?? '',
                'user_type' => $userDetails->customer_type ?? '',
                'role' => $userDetails->customer_position ?? '',
                'additional_email' => $userDetails->additional_email ?? '',
                'customer_notes' => $userNotes->note ?? '',
                'tag_id' => $userTags,  // Return an array of tag IDs
            ]);
        }

        return response()->json(null);
    }

    public function automation()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 3;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $users = User::all();
        $roles = Role::all();
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();

        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all(); // Fetch all tags

        // Fetch all cities initially
        // $cities = LocationCity::all();
        // $cities1 = LocationCity::all();


        return view('dashboard.automation_form_submission', compact('users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags',));
    }
    public function showForm()
    {
        // Define an array with pre-filled values
        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'display_name' => 'John D.',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'address' => '123 Main St, Anytown, USA',
            'state' => 'CA',
            'zip' => '12345',
            'country' => 'CA', // Initially selected country
            'occupation' => 'developer',
            'self_description' => 'I am a software developer with 5 years of experience in web development.',
            'interests' => ['Technology', 'Music'], // Default selected interests
        ];

        // Pass the array to the view
        return view('dashboard.automation_array', compact('formData'));
    }
}
