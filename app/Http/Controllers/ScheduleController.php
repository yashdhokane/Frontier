<?php

namespace App\Http\Controllers;

use App\Models\Appliances;
use App\Models\AppliancesType;
use App\Models\BusinessHours;
use App\Models\CustomerUserAddress;
use App\Models\Event;
use App\Models\JobActivity;
use App\Models\Jobfields;
use App\Models\RoutingTrigger;


use App\Models\RoutingJOb;
use App\Models\JobAppliances;
use App\Models\JobAssign;
use App\Models\JobDetails;
use App\Models\JobModel;
use App\Models\LocationCity;
use App\Models\LocationServiceArea;
use App\Models\LocationState;
use App\Models\Manufacturer;
use App\Models\JobTechEvent;
use App\Models\User;
use App\Models\Service;
use App\Models\Products;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\ServiceCategory;
use App\Models\SiteJobFields;
use App\Models\SiteJobTitle;
use App\Models\SiteLeadSource;
use App\Models\SiteTags;
use App\Models\TimeZone;
use App\Models\UserAppliances;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Storage;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;

class ScheduleController extends Controller
{



    public function index(Request $request)
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 30;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $users = User::all();

        $roles = Role::all();

        $locationStates = LocationState::all();

        $locationStates1 = LocationState::all();



        $leadSources = SiteLeadSource::all();

        $tags = SiteTags::all(); // Fetch all tags



        // Fetch all cities initially

        $cities = LocationCity::all();

        $cities1 = LocationCity::all();

        $data = $request->all();

        if (isset($data['date']) && !empty($data['date'])) {
            $currentDate = Carbon::parse($data['date']);
        } else {
            $currentDate = Carbon::now($timezone_name);
        }

        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        // Query the business hours for the given day
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('D, F j, Y');

        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');

        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $filterDate = $currentDate->format('Y-m-d');

        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');

        $user_array = [];

        $user_data_array = [];

        $assignment_arr = [];

        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {

            foreach ($user_array as $key => $value) {

                $assignment_arr[$value] = [];

                $result = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->get();


                if (isset($result) && !empty($result->count())) {
                    foreach ($result as $key2 => $value2) {
                        $value2->JobModel = JobModel::find($value2->job_id);
                        $datetimeString = $value2->start_date_time;
                        $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                        $time = date("h:i A", strtotime($newFormattedDateTime));
                        $assignment_arr[$value][$time][] = $value2;
                    }
                }

                $schedule_arr[$value] = [];

                $schedule = Schedule::with('JobModel', 'technician')->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")
                    ->where('show_on_schedule', 'yes')->get();
                if (isset($schedule) && !empty($schedule->count())) {
                    foreach ($schedule as $k => $item) {
                        $datetimeString = $item->start_date_time;
                        $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                        $time = date("h:i A", strtotime($newFormattedDateTime));
                        $schedule_arr[$value][$time][] = $item;
                    }
                }
            }
        }

        $current_time = Carbon::now($timezone_name)->format('h:i A');
        $new_currentDate = $currentDate->format('Y-m-d');

        $data = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', '=', $new_currentDate)
            ->where('job_assigned.assign_status', 'active')
            ->orderBy('job_assigned.pending_number', 'asc')
            ->get();

        return view('schedule.index', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr', 'hours', 'current_time', 'data'));
    }
    public function schedule_new(Request $request)
    {
        // Initial setup
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 30;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck !== true) {
            return $permissionCheck;
        }

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $users = User::all();
        $roles = Role::all();
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all(); // Fetch all tags
        $cities = LocationCity::all();
        $cities1 = LocationCity::all();

        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);
        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('D, F j, Y');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');
        $filterDate = $currentDate->format('Y-m-d');
        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');

        $user_array = [];
        $user_data_array = [];
        $assignment_arr = [];
        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();
        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {
            foreach ($user_array as $value) {
                // For job assignments
                $assignmentResults = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'schedule.schedule_type',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('schedule', 'schedule.job_id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                // Attach JobModel to assignmentResults
                foreach ($assignmentResults as $assignment) {
                    $assignment->JobModel = JobModel::with('user', 'technician', 'jobassignname', 'addresscustomer')->find($assignment->job_id);
                    $datetimeString = $assignment->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $assignment_arr[$value][$time][] = $assignment;
                }

                // For schedules
                $scheduleResults = Schedule::with('JobModel', 'technician')
                    ->where('show_on_schedule', 'yes')
                    ->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                foreach ($scheduleResults as $schedule) {
                    $datetimeString = $schedule->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $schedule_arr[$value][$time][] = $schedule;
                }
            }
        }

        $current_time = Carbon::now($timezone_name)->format('h:i A');
        $new_currentDate = $currentDate->format('Y-m-d');

        $data = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', '=', $new_currentDate)
            ->where('job_assigned.assign_status', 'active')
            ->orderBy('job_assigned.pending_number', 'asc')
            ->get();



        return view('schedule.schedule_new', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr', 'hours', 'current_time', 'data'));
    }


    public function getTableContent(Request $request)
    {


        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $nowTime = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');

        $users = User::all();

        $roles = Role::all();

        $locationStates = LocationState::all();

        $locationStates1 = LocationState::all();



        $leadSources = SiteLeadSource::all();

        $tags = SiteTags::all(); // Fetch all tags



        // Fetch all cities initially

        $cities = LocationCity::all();

        $cities1 = LocationCity::all();

        $data = $request->all();

        if (isset($data['date']) && !empty($data['date'])) {
            $currentDate = Carbon::parse($data['date']);
        } else {
            $currentDate = Carbon::now($timezone_name);
        }

        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        // Query the business hours for the given day
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('l, F j, Y');

        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');

        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $filterDate = $currentDate->format('Y-m-d');

        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');

        $user_array = [];

        $user_data_array = [];

        $assignment_arr = [];

        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {

            foreach ($user_array as $key => $value) {

                $assignment_arr[$value] = [];

                $result = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->where('job_assigned.end_date_time', '>=', $nowTime)
                    ->get();

                if (isset($result) && !empty($result->count())) {
                    foreach ($result as $key2 => $value2) {
                        $datetimeString = $value2->start_date_time;
                        $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                        $time = date("h:i A", strtotime($newFormattedDateTime));
                        $assignment_arr[$value][$time][] = $value2;
                    }
                }

                $schedule_arr[$value] = [];

                $schedule = Schedule::with('JobModel', 'technician')->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")->where('end_date_time', '>=', $nowTime)->get();
                if (isset($schedule) && !empty($schedule->count())) {
                    foreach ($schedule as $k => $item) {
                        $datetimeString = $item->start_date_time;
                        $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                        $time = date("h:i A", strtotime($newFormattedDateTime));
                        $schedule_arr[$value][$time][] = $item;
                    }
                }
            }
        }

        $current_time = Carbon::now($timezone_name)->format('h:i A');

        return view('schedule.getTableContent', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr', 'hours', 'current_time'));
    }

    public function create_job(Request $request, $id, $t, $d)
    {

        if (isset($id) && !empty($id)) {

            $time = str_replace(" ", ":00 ", $t);

            $appliances = DB::table('appliance_type')->get();

            $manufacturers = DB::table('manufacturers')->get();

            $date = $d;

            $dateTime = Carbon::parse("$date $time");
            $datenew = Carbon::parse($date);
            $currentDay = $datenew->format('l');
            $currentDayLower = strtolower($currentDay);
            // Query the business hours for the given day
            $hours = BusinessHours::where('day', $currentDayLower)->first();

            // Calculate time intervals (example)
            $timeIntervals = [];
            $current = strtotime($hours->start_time);
            $end = strtotime($hours->end_time);
            $interval = 30 * 60; // Interval in seconds (30 minutes)

            while ($current <= $end) {
                $timeIntervals[] = date('H:i', $current);
                $current += $interval;
            }

            // Example existing date and time
            $date = $dateTime->format('Y-m-d'); // Current date

            $tags = SiteTags::all();

            $dateTime = $dateTime->format('Y-m-d H:i:s');

            $technician = User::where('id', $id)->first();

            $getServices = Service::all();
            $serviceCat = ServiceCategory::with('Services')->get();

            $getProduct = Products::whereNotNull('base_price')->where('status', 'Publish')->get();
            $locationStates = LocationState::all();

            $leadSources = SiteLeadSource::all();

            $tags = SiteJobFields::orderBy('field_name', 'asc')->get(); // Fetch all tags

            $site = SiteTags::all();

            $jobTitle = SiteJobTitle::orderBy('field_name', 'asc')->get();

            return view('schedule.create_job', compact('tags', 'leadSources', 'locationStates', 'technician', 'dateTime', 'manufacturers', 'appliances', 'getServices', 'getProduct', 'tags', 'hours', 'time', 'serviceCat', 'site', 'date', 'timeIntervals', 'jobTitle'));
        }
    }
    public function create(Request $request)
    {

        $data = $request->all();

        if (isset($data['id']) && !empty($data['id'])) {

            $time = str_replace(" ", ":00 ", $data['time']);

            $appliances = DB::table('appliances')->get();

            $manufacturers = DB::table('manufacturers')->get();

            $date = $data['date'];

            $dateTime = Carbon::parse("$date $time");

            $tags = SiteTags::all();

            $dateTime = $dateTime->format('Y-m-d H:i:s');

            $technician = User::join('user_address', 'user_address.user_id', 'users.id')->where('id', $data['id'])->first();

            $getServices = Service::where('service_cost', '!=', 0)->get();

            $getProduct = Products::whereNotNull('base_price')->where('status', 'Publish')->get();

            return view('schedule.create', compact('technician', 'dateTime', 'manufacturers', 'appliances', 'getServices', 'getProduct', 'tags'));
        }
    }

    public function autocompleteCustomer(Request $request)
    {
        $data = $request->all();

        $customers = '';

        $pendingJobs = '';

        if (isset($data['name']) && !empty($data['name'])) {

            $filterCustomer = User::where('name', 'LIKE', '%' . $data['name'] . '%')
                ->where('role', 'customer')
                ->get();

            $filterJobs = DB::table('jobs')->select('jobs.job_title', 'jobs.id', 'jobs.customer_id', 'jobs.address', 'jobs.technician_id', 'users.name as customer_name', 'technician.name as technician_name', 'jobs.created_at', 'appliances.appliance_name', 'user_address.state_id as state_id')
                ->join('appliances', 'appliances.appliance_id', 'jobs.appliances_id')
                ->join('user_address', 'user_address.user_id', 'jobs.customer_id')
                ->join('users', 'users.id', 'jobs.customer_id')
                ->join('users as technician', 'technician.id', 'jobs.technician_id')
                // ->where('users.name', 'LIKE', '%' . $data['name'] . '%')
                ->get();

            if (isset($filterCustomer) && !empty($filterCustomer->count())) {

                foreach ($filterCustomer as $key => $value) {

                    $getCustomerAddress = DB::table('user_address')
                        ->select('user_address.address_line1', 'user_address.address_line2', 'user_address.city', 'location_states.state_name', 'location_states.state_code', 'user_address.zipcode')
                        ->join('location_states', 'location_states.state_id', 'user_address.state_id')
                        ->where('user_id', $value->id)
                        ->first();

                    $imagePath = public_path('images/customer/' . $value->user_image);

                    if (file_exists($imagePath) && !empty($value->user_image)) {
                        $imageSrc = asset('public/images/customer') . '/' . $value->user_image;
                    } else {
                        $imageSrc = asset('public/images/login_img_bydefault.png');
                    }

                    $customers .= '<div class="customer_sr_box selectCustomer" data-customer-id="' . $value->id . '" data-id="' . $value->id . '" data-name="' . $value->name . '"><div class="row">';
                    $customers .= '<div class="col-md-12"><h6 class="font-weight-medium mb-0">' . $value->name . ' ';
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city)) {
                        $customers .= '<small class="text-muted">' . $getCustomerAddress->city . ' ' . $getCustomerAddress->state_code . ' </small>';
                    }
                    $customers .= '</h6><p class="text-muted test">';
                    if (isset($value->mobile) && !empty($value->mobile)) {
                        $customers .= $value->mobile . ' / ';
                    }
                    if (isset($value->email) && !empty($value->email)) {
                        $customers .= $value->email;
                    }
                    if (isset($value->email) && !empty($value->email) && isset($value->email) && !empty($value->email)) {
                        $customers .= '<br />';
                    }
                    if (isset($getCustomerAddress->address_line1) && !empty($getCustomerAddress->address_line1)) {
                        $customers .= $getCustomerAddress->address_line1 . ', ';
                    }
                    if (isset($getCustomerAddress->address_line2) && !empty($getCustomerAddress->address_line2)) {
                        $customers .= $getCustomerAddress->address_line2 . ', ';
                    }
                    if (isset($getCustomerAddress->address_line2) && !empty($getCustomerAddress->address_line2)) {
                        $customers .= $getCustomerAddress->address_line2 . ', ';
                    }
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city)) {
                        $customers .= $getCustomerAddress->city . ', ';
                    }
                    if (isset($getCustomerAddress->state_name) && !empty($getCustomerAddress->state_name)) {
                        $customers .= $getCustomerAddress->state_name . ', ';
                    }
                    if (isset($getCustomerAddress->zipcode) && !empty($getCustomerAddress->zipcode)) {
                        $customers .= $getCustomerAddress->zipcode;
                    }


                    $customers .= '</p></div></div></div>';
                }
            }

            if (isset($filterJobs) && !empty($filterJobs->count())) {
                foreach ($filterJobs as $key => $value) {

                    $createdDate = Carbon::parse($value->created_at);

                    $pendingJobs .= '<div class="pending_jobs2" data-technician-name="' . $value->technician_name . '" data-customer-name="' . $value->customer_name . '" data-customer-id="' . $value->customer_id . '" data-technician-id="' . $value->technician_id . '" data-id="' . $value->id . '" data-address="' . $value->address . '" data-state-id="' . $value->state_id . '"><div class="row"><div class="col-md-12">';
                    $pendingJobs .= '<h6 class="font-weight-medium mb-0">' . $value->job_title . '</h6></div>';
                    $pendingJobs .= '<div class="col-md-6 reschedule_job">Customer: ' . $value->customer_name . '</div>';
                    $pendingJobs .= '<div class="col-md-6 reschedule_job" style="display: contents;">Technician: ' . $value->technician_name . '</div>';
                    $pendingJobs .= '<div class="col-md-12 reschedule_job">' . $value->appliance_name . ' (On ' . $createdDate->format('Y-m-d') . ')</div></div></div>';
                }
            }
        }

        return ['customers' => $customers, 'pendingJobs' => $pendingJobs];
    }

    public function autocompleteTechnician(Request $request)
    {
        $query = $request->get('query');
        $filterResult = User::where('name', 'LIKE', '%' . $query . '%')->where('role', 'technician')->get();
        return response()->json($filterResult);
    }

    public function autocompleteServices(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Service::select('service_name as name')->where('service_name', 'LIKE', '%' . $query . '%')->get();
        return response()->json($filterResult);
    }

    public function autocompleteProduct(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Products::select('product_name as name')->where('product_name', 'LIKE', '%' . $query . '%')->get();
        return response()->json($filterResult);
    }

    public function getCustomerDetails(Request $request)
    {
        $data = $request->all();

        $customer = [];

        if (isset($data['id']) && !empty($data['id'])) {

            $getCustomerDetails = User::where('id', $data['id'])->first();

            if (isset($getCustomerDetails) && !empty($getCustomerDetails)) {
                $customer = $getCustomerDetails->toArray();
                $getCustomerAddressDetails = DB::table('user_address')->where('user_id', $getCustomerDetails->id)->get()->toArray();
                $customer['address'] = $getCustomerAddressDetails;
            }
        }

        return response()->json($customer);
    }

    public function getServicesAndProductDetails(Request $request)
    {
        $data = $request->all();

        $product = [];

        $serives = [];

        if (isset($data['searchProduct']) && !empty($data['searchProduct'])) {

            $getProductDetails = Products::where('product_name', $data['searchProduct'])->where('status', 'Publish')->first();

            if (isset($getProductDetails) && !empty($getProductDetails)) {
                $product = $getProductDetails->toArray();
            }

            $getServicesDetails = Service::where('service_name', $data['searchServices'])->first();

            if (isset($getServicesDetails) && !empty($getServicesDetails)) {
                $serives = $getServicesDetails->toArray();
            }
        }

        return ['product' => $product, 'serives' => $serives];
    }

    public function getProductDetails(Request $request)
    {
        $data = $request->all();

        $product = [];

        if (isset($data['id']) && !empty($data['id'])) {

            $getProductDetails = Products::where('product_id', $data['id'])->where('status', 'Publish')->first();

            if (isset($getProductDetails) && !empty($getProductDetails)) {
                $product = $getProductDetails->toArray();
            }
        }
        $customer = CustomerUserAddress::with('locationStateName')->where('user_id', $request->customerId)->first();
        $statecode = $customer->locationStateName;

        return response()->json([
            'product' => $product,
            'statecode' => $statecode
        ]);
    }

    public function getServicesDetails(Request $request)
    {
        $data = $request->all();

        $serives = [];

        if (isset($data['id']) && !empty($data['id'])) {

            $getServicesDetails = Service::where('service_id', $data['id'])->first();

            if (isset($getServicesDetails) && !empty($getServicesDetails)) {
                $serives = $getServicesDetails->toArray();
            }
        }
        $customer = CustomerUserAddress::with('locationStateName')->where('user_id', $request->customerId)->first();
        $statecode = $customer->locationStateName;

        return response()->json([
            'serives' => $serives,
            'statecode' => $statecode
        ]);
    }
    public function createSchedule(Request $request)
    {

        $data = $request->all();
        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $data['job_title'] = $request->job_titleSelect ?: $request->job_titleInput;

        if (isset($data) && !empty($data)) {

            if (isset($data['job_id']) && !empty($data['job_id'])) {
                $newFormattedDateTime = Carbon::parse($data['datetime'])->subHours($time_interval)->format('Y-m-d H:i:s');
                $start_date_time = Carbon::parse($newFormattedDateTime);

                $duration = (int) $data['duration'];

                $end_date_time = $start_date_time->copy()->addMinutes($duration);

                $technician = User::where('id', $data['technician_id'])->first();

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $getCustomerDetails = User::with('userAddress')
                    ->where('id', $data['customer_id'])
                    ->first();

                do {
                    $randomSixDigit = mt_rand(100000, 999999);
                    $exists = DB::table('jobs')->where('job_code', $randomSixDigit)->exists();
                } while ($exists);

                if (is_array($request->tags)) {
                    $tagIds = implode(',', $request->tags);
                } else {
                    $tagIds = '';
                }

                $customer_name = User::where('id', $data['customer_id'])->first();
                $technician_name = User::where('id', $data['technician_id'])->first();

                $jobsData = [
                    'job_code' => (isset($randomSixDigit) && !empty($randomSixDigit)) ? $randomSixDigit : '',
                    'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'appliances_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : $data['exist_appl_id'],
                    'description' => (isset($data['job_description']) && !empty($data['job_description'])) ? $data['job_description'] : '',
                    'priority' => (isset($data['priority']) && !empty($data['priority'])) ? $data['priority'] : '',
                    'warranty_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
                    'tax' => (isset($data['tax_total']) && !empty($data['tax_total'])) ? $data['tax_total'] : '',
                    'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                    'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                    'subtotal' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                    'is_confirmed' => (isset($data['is_confirmed']) && !empty($data['is_confirmed'])) ? $data['is_confirmed'] : 'no',
                    'is_published' => (isset($data['is_published']) && !empty($data['is_published'])) ? $data['is_published'] : 'no',
                    'tag_ids' => $tagIds,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobId = DB::table('jobs')->where('id', $data['job_id'])->update($jobsData);

                $jobtech = JobTechEvent::where('job_id', $data['job_id'])->first();
                $jobtech->job_schedule = $start_date_time;
                $jobtech->job_enroute = null;
                $jobtech->job_start = null;
                $jobtech->job_end = null;
                $jobtech->job_invoice = null;
                $jobtech->job_payment = null;

                $jobtech->update();

                $jobNotes = [
                    'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobNotesID = DB::table('job_notes')->where('job_id', $data['job_id'])->update($jobNotes);

                $JobAssignedData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                    'assign_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'assign_description' => (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '',
                    'duration' => (isset($data['duration']) && !empty($data['duration'])) ? $data['duration'] : '',
                    'start_date_time' => $start_date_time,
                    'end_date_time' => $end_date_time,
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'start_slot' => $start_date_time->format('H'),
                    'end_slot' => $end_date_time->format('H'),
                    'technician_note_id' => $jobNotesID
                ];

                $jobAssignedID = DB::table('job_assigned')->where('job_id', $data['job_id'])->update($JobAssignedData);

                if (isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])) {

                    $userappl = [
                        'appliance_id' => (isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])) ? $data['exist_appl_id'] : '',
                    ];
                    $addAppliancesUser = DB::table('job_appliance')->where('job_id', $data['job_id'])->update($userappl);
                } else {
                    $jobDetails = [
                        'user_id' => $data['customer_id'],
                        'appliance_type_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : '',
                        'model_number' => (isset($data['model_number']) && !empty($data['model_number'])) ? $data['model_number'] : '',
                        'serial_number' => (isset($data['serial_number']) && !empty($data['serial_number'])) ? $data['serial_number'] : '',
                        'manufacturer_id' => (isset($data['manufacturer']) && !empty($data['manufacturer'])) ? $data['manufacturer'] : '',
                    ];

                    $userappliances = DB::table('user_appliances')->insertGetId($jobDetails);

                    $userappl = [
                        'job_id' => $data['job_id'],
                        'appliance_id' => $userappliances,
                    ];
                    $addAppliancesUser = DB::table('job_appliance')->insertGetId($userappl);
                }

                if (isset($data['service_id']) && !empty($data['service_id'])) {

                    $serviceData = [
                        'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                if (isset($data['product_id']) && !empty($data['product_id'])) {

                    $productData = [
                        'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                    ];

                    $productDataInsert = DB::table('job_product_items')->where('job_id', $data['job_id'])->update($productData);
                }

                if ($request->hasFile('photos')) {
                    $fileData = [];

                    foreach ($request->file('photos') as $file) {
                        // Generate a unique filename
                        $fileName = $data['job_id'] . '_' . $file->getClientOriginalName();

                        // Generate a unique directory name based on user ID and timestamp
                        $directoryName = $data['job_id'];

                        // Construct the full path for the directory
                        $directoryPath = public_path('uploads/jobs/' . $directoryName);

                        // Ensure the directory exists; if not, create it
                        if (!file_exists($directoryPath)) {
                            mkdir($directoryPath, 0777, true);
                        }

                        // Move the uploaded file to the unique directory
                        $file->move($directoryPath, $fileName);

                        // Save file details to the database
                        $fileData[] = [
                            'job_id' => $data['job_id'],
                            'user_id' => auth()->id(),
                            'path' => $directoryPath . '/', // Store the full path
                            'filename' => $file->getClientOriginalName(),
                            'type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'created_at' => now(), // Use Laravel's helper function for timestamps
                            'updated_at' => now()
                        ];
                    }

                    // Insert file data into the database
                    $fileDataInsert = DB::table('job_files')->insert($fileData);
                }

                if ($jobId && $data['scheduleType']) {

                    $schedule = new Schedule();

                    $schedule->schedule_type = $data['scheduleType'];
                    $schedule->job_id = $data['job_id'];
                    $schedule->start_date_time = $start_date_time;
                    $schedule->end_date_time = $end_date_time;
                    $schedule->technician_id = $data['technician_id'];
                    $schedule->added_by = auth()->user()->id;
                    $schedule->updated_by = auth()->user()->id;
                    $schedule->show_on_schedule = !empty($data['show_on_schedule']) ? $data['show_on_schedule'] : 'no';

                    $schedule->save();

                    $scheduleId = $schedule->id;
                }
                $now = Carbon::now($timezone_name);
                $formattedDate = $start_date_time->format('D, M j');
                $formattedTime = $now->format('g:ia');
                $formattedDateTime = "{$formattedDate} at {$formattedTime}";
                $activity = 'Job Re-Scheduled for ' . $formattedDateTime;
                app('JobActivityManager')->addJobActivity($data['job_id'], $activity);
                app('sendNotices')(
                    "Reschedule Job",
                    "Reschedule Job (#{$jobId} - {$customer_name->name}) added by {$technician_name->name}",
                    url()->current(),
                    'job'
                );

                $height_slot = $duration / 60;
                $height_slot_px = $height_slot * 80 - 10;

                $returnDate = '<div class="dts mb-1 edit_schedule flexibleslot" data-bs-toggle="modal" data-bs-target="#edit" style="cursor: pointer;height:' . $height_slot_px . 'px;background:' . $technician->color_code . ';" data-id="' . $jobId . '">
                    <h5 style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">' . $randomSixDigit . ' <br>' . $data['job_title'] . '</p>
                    <p style="font-size: 12px;">' . $getCustomerDetails->userAddress->city . ',' . $getCustomerDetails->userAddress->state_name . '</p></div>';

                return ['html' => $returnDate, 'start_date' => $start_date_time->copy()->format('g'), 'technician_id' => $data['technician_id'], 'schedule_id' => $scheduleId];
            } else {

                $technician = User::where('id', $data['technician_id'])->first();

                $customer = User::where('id', $data['customer_id'])->first();

                $newFormattedDateTime = Carbon::parse($data['datetime'])->subHours($time_interval)->format('Y-m-d H:i:s');


                $start_date_time = Carbon::parse($newFormattedDateTime);

                $duration = (int) $data['duration'];

                $end_date_time = $start_date_time->copy()->addMinutes($duration);

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $getCustomerDetails = User::select('user_address.*', 'location_states.state_code as customer_state')
                    ->join('user_address', 'user_address.user_id', '=', 'users.id')
                    ->join('location_states', 'location_states.state_id', '=', 'user_address.state_id')
                    ->where('users.id', $data['customer_id'])
                    ->where('user_address.address_type', $data['customer_address'])
                    ->first();


                if (is_array($request->tags)) {
                    $tagIds = implode(',', $request->tags);
                } else {
                    $tagIds = '';
                }

                do {
                    $randomSixDigit = mt_rand(100000, 999999);
                    $exists = DB::table('jobs')->where('job_code', $randomSixDigit)->exists();
                } while ($exists);
                $customer_name = User::where('id', $data['customer_id'])->first();
                $technician_name = User::where('id', $data['technician_id'])->first();

                if ($getCustomerDetails->customer_state == 'NY') {
                    $tax_details = '4% for NY';
                } elseif ($getCustomerDetails->customer_state == 'TX') {
                    $tax_details = '6.25% for TX';
                }


                $jobsData = [
                    'job_code' => (isset($randomSixDigit) && !empty($randomSixDigit)) ? $randomSixDigit : '',
                    'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'appliances_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : $data['exist_appl_id'],
                    'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'warranty_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
                    'warranty_ticket' => (isset($data['warranty_ticket']) && !empty($data['warranty_ticket'])) ? $data['warranty_ticket'] : '',
                    'description' => (isset($data['job_description']) && !empty($data['job_description'])) ? trim($data['job_description']) : '',
                    'priority' => (isset($data['priority']) && !empty($data['priority'])) ? $data['priority'] : '',
                    'service_area_id' => (isset($data['service_area_id']) && !empty($data['service_area_id'])) ? $data['service_area_id'] : 0,
                    'tax' => (isset($data['tax_total']) && !empty($data['tax_total'])) ? $data['tax_total'] : '',
                    'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                    'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                    'subtotal' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                    'address_type' => (isset($data['customer_address']) && !empty($data['customer_address'])) ? $data['customer_address'] : '',
                    'address' => (isset($getCustomerDetails->address_line1) && !empty($getCustomerDetails->address_line1)) ? $getCustomerDetails->address_line1 : '',
                    'city' => (isset($getCustomerDetails->city) && !empty($getCustomerDetails->city)) ? $getCustomerDetails->city : '',
                    'state' => (isset($getCustomerDetails->customer_state) && !empty($getCustomerDetails->customer_state)) ? $getCustomerDetails->customer_state : '',
                    'tax_details' => (isset($tax_details) && !empty($tax_details)) ? $tax_details : '',
                    'zipcode' => (isset($getCustomerDetails->zipcode) && !empty($getCustomerDetails->zipcode)) ? $getCustomerDetails->zipcode : '',
                    'latitude' => (isset($getCustomerDetails->latitude) && !empty($getCustomerDetails->latitude)) ? $getCustomerDetails->latitude : 0,
                    'longitude' => (isset($getCustomerDetails->longitude) && !empty($getCustomerDetails->longitude)) ? $getCustomerDetails->longitude : 0,
                    'is_confirmed' => (isset($data['is_confirmed']) && !empty($data['is_confirmed'])) ? $data['is_confirmed'] : 'no',
                    'is_published' => (isset($data['is_published']) && !empty($data['is_published'])) ? $data['is_published'] : 'no',
                    'added_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'job_field_ids' => 0,
                    'tag_ids' => $tagIds,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobId = DB::table('jobs')->insertGetId($jobsData);

                $jobtech = new JobTechEvent();
                $jobtech->job_id = $jobId;
                $jobtech->job_schedule = $start_date_time;

                $jobtech->save();

                $jobNotes = [
                    'job_id' => $jobId,
                    'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                    'added_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobNotesID = DB::table('job_notes')->insertGetId($jobNotes);

                $JobAssignedData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                    'job_id' => $jobId,
                    'assign_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'assign_description' => (isset($data['job_description']) && !empty($data['job_description'])) ? $data['job_description'] : '',
                    'duration' => (isset($data['duration']) && !empty($data['duration'])) ? $data['duration'] : '',
                    'start_date_time' => $start_date_time,
                    'end_date_time' => $end_date_time,
                    'added_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'start_slot' => $start_date_time->format('H'),
                    'end_slot' => $end_date_time->format('H'),
                    'technician_note_id' => $jobNotesID
                ];

                $jobAssignedID = DB::table('job_assigned')->insertGetId($JobAssignedData);

                if (isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])) {

                    $userappl = [
                        'job_id' => $jobId,
                        'appliance_id' => (isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])) ? $data['exist_appl_id'] : '',
                    ];
                    $addAppliancesUser = DB::table('job_appliance')->insertGetId($userappl);
                } else {
                    $jobDetails = [
                        'user_id' => $data['customer_id'],
                        'appliance_type_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : '',
                        'model_number' => (isset($data['model_number']) && !empty($data['model_number'])) ? $data['model_number'] : '',
                        'serial_number' => (isset($data['serial_number']) && !empty($data['serial_number'])) ? $data['serial_number'] : '',
                        'manufacturer_id' => (isset($data['manufacturer']) && !empty($data['manufacturer'])) ? $data['manufacturer'] : '',
                    ];

                    $userappliances = DB::table('user_appliances')->insertGetId($jobDetails);

                    $userappl = [
                        'job_id' => $jobId,
                        'appliance_id' => $userappliances,
                    ];
                    $addAppliancesUser = DB::table('job_appliance')->insertGetId($userappl);
                }


                if (isset($data['services']) && !empty($data['services'])) {
                    foreach ($data['services'] as $index => $serviceId) {
                        $serviceData = [
                            'service_id' => $serviceId,
                            'job_id' => $jobId,
                            'base_price' => isset($data['service_cost'][$index]) ? $data['service_cost'][$index] : 0,
                            'tax' => isset($data['service_tax'][$index]) ? $data['service_tax'][$index] : 0,
                            'discount' => isset($data['service_discount_amount'][$index]) ? $data['service_discount_amount'][$index] : 0,
                            'sub_total' => isset($data['service_total'][$index]) ? $data['service_total'][$index] : 0,
                        ];

                        $serviceDataInsert = DB::table('job_service_items')->insertGetId($serviceData);
                    }
                }

                if (isset($data['products']) && !empty($data['products'])) {
                    foreach ($data['products'] as $index => $productId) {
                        $productData = [
                            'product_id' => $productId,
                            'job_id' => $jobId,
                            'base_price' => isset($data['product_cost'][$index]) ? $data['product_cost'][$index] : 0,
                            'tax' => isset($data['product_tax'][$index]) ? $data['product_tax'][$index] : 0,
                            'discount' => isset($data['product_discount_amount'][$index]) ? $data['product_discount_amount'][$index] : 0,
                            'sub_total' => isset($data['product_total'][$index]) ? $data['product_total'][$index] : 0,
                        ];

                        $productDataInsert = DB::table('job_product_items')->insertGetId($productData);
                    }
                }


                if ($request->hasFile('photos')) {
                    $fileData = [];

                    foreach ($request->file('photos') as $file) {
                        // Generate a unique filename
                        $fileName = $jobId . '_' . $file->getClientOriginalName();

                        // Generate a unique directory name based on job ID
                        $directoryName = $jobId;

                        // Construct the full path for the directory
                        $directoryPath = public_path('uploads/jobs/' . $directoryName);

                        // Ensure the directory exists; if not, create it
                        if (!file_exists($directoryPath)) {
                            mkdir($directoryPath, 0777, true);
                        }

                        // Move the uploaded file to the unique directory
                        $file->move($directoryPath, $fileName);

                        // Save file details to the database
                        $fileData[] = [
                            'job_id' => $jobId,
                            'user_id' => auth()->id(),
                            'path' => $directoryPath . '/', // Store the full path
                            'filename' => $file->getClientOriginalName(),
                            // 'type' => $file->getMimeType(),
                            // 'size' => $file->getSize(),
                            'created_at' => now(), // Use Laravel's helper function for timestamps
                            'updated_at' => now()
                        ];
                    }

                    // Insert file data into the database
                    $fileDataInsert = DB::table('job_files')->insert($fileData);
                }
                // for job activity

                if ($jobId && $data['scheduleType']) {

                    $schedule = new Schedule();

                    $schedule->schedule_type = $data['scheduleType'];
                    $schedule->job_id = $jobId;
                    $schedule->start_date_time = $start_date_time;
                    $schedule->end_date_time = $end_date_time;
                    $schedule->technician_id = $data['technician_id'];
                    $schedule->added_by = auth()->user()->id;
                    $schedule->updated_by = auth()->user()->id;
                    $schedule->show_on_schedule = !empty($data['show_on_schedule']) ? $data['show_on_schedule'] : 'no';

                    $schedule->save();
                    $scheduleId = $schedule->id;
                }
                $now = Carbon::now($timezone_name);
                $formattedDate = $start_date_time->format('D, M j');
                $formattedTime = $now->format('g:ia');
                $formattedDateTime = "{$formattedDate} at {$formattedTime}";

                $activity = 'Job scheduled for ' . $formattedDateTime;
                app('JobActivityManager')->addJobActivity($jobId, $activity);
                app('sendNotices')(
                    "New Job",
                    "New Job (#{$jobId} - {$customer_name->name}) added by {$technician_name->name}",
                    url()->current(),
                    'job'
                );

                $message = 'Hello, Your Job is schedule.';
                // $to = '+919960391046'; // Recipient's phone number
                $to = '+917030467187'; // Recipient's phone number

                $response = app('SmsService')->sendSms($message, $to);


                $height_slot = $duration / 60;
                $height_slot_px = $height_slot * 80 - 10;

                $returnDate = '<div class="dts mb-1 edit_schedule flexibleslot" data-bs-toggle="modal" data-bs-target="#edit" style="cursor: pointer;height:' . $height_slot_px . 'px;background:' . $technician->color_code . ';" data-id="' . $jobId . '">
                    <h5 style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">' . $customer->name . '</h5>
                    <p style="font-size: 11px;"><i class="fas fa-clock"></i>' . $start_date_time->format('h a') . ' -- ' . $randomSixDigit . ' <br>' . $data['job_title'] . '</p>
                    <p style="font-size: 12px;">' . $getCustomerDetails->city . ',' . $getCustomerDetails->state_name . '</p></div>';

                return [
                    'html' => $returnDate,
                    'schedule_id' => $scheduleId,
                ];
            }
        }
    }

    public function edit(Request $request)
    {

        $data = $request->all();

        if (isset($data['id']) && !empty($data['id'])) {

            $time = str_replace(" ", ":00 ", $data['time']);

            $appliances = DB::table('appliances')->get();

            $manufacturers = DB::table('manufacturers')->get();

            $date = $data['date'];

            $dateTime = Carbon::parse("$date $time");

            $dateTime = $dateTime->format('Y-m-d H:i:s');

            $technician = User::join('user_address', 'user_address.user_id', 'users.id')->where('id', $data['id'])->first();

            $getServices = Service::where('service_cost', '!=', 0)->get();

            $getProduct = Products::whereNotNull('base_price')->where('status', 'Publish')->get();

            $jobId = $request->job_id;

            $job = JobModel::with('jobDetails', 'JobAssign', 'JobNote', 'jobserviceinfo', 'jobproductinfo', 'technician', 'user')
                ->where('id', $jobId)->first();

            $customer1 = CustomerUserAddress::with('locationStateName')->where('user_id', $job->customer_id)->first();
            $statecode = $customer1->locationStateName;

            return view('schedule.edit', compact('technician', 'dateTime', 'manufacturers', 'appliances', 'getServices', 'getProduct', 'job', 'statecode'));
        }
    }

    public function updateSchedule(Request $request)
    {

        $data = $request->all();

        try {

            if (isset($data) && !empty($data)) {

                $start_date_time = Carbon::parse($data['start_date_time']);

                $old_start_date_time = Carbon::parse($data['old_start_date_time']);

                $technician = User::where('id', $data['technician_id'])->first();

                $current_date = Carbon::now();

                $result = $start_date_time->gt($old_start_date_time) ? $start_date_time->copy()->format('g') : null;

                //$result = $old_start_date_time->copy()->greaterThan($start_date_time) ? null  : $start_date_time->copy()->format('g');

                $end_date_time = Carbon::parse($data['end_date_time']);

                $duration = $start_date_time->copy()->diffInMinutes($end_date_time->copy());

                $jobDetails = DB::table('jobs')->where('id', $data['job_id'])->first();

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $jobsData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'tax' => $service_tax + $product_tax,
                    'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                    'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                    'subtotal' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                    'updated_by' => auth()->id(),
                    'job_field_ids' => 0,
                    'tag_ids' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobId = DB::table('jobs')->where('id', $data['job_id'])->update($jobsData);

                $jobNotes = [
                    'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobNotesID = DB::table('job_notes')->where('job_id', $data['job_id'])->update($jobNotes);

                $JobAssignedData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'duration' => $duration,
                    'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                    'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'start_slot' => $start_date_time->format('H'),
                    'end_slot' => $end_date_time->format('H'),
                ];

                $jobAssignedID = DB::table('job_assigned')->where('job_id', $data['job_id'])->update($JobAssignedData);

                if (isset($data['service_id']) && !empty($data['service_id']) && (isset($data['service_quantity']) && !empty($data['service_quantity']) && $data['service_quantity'] == 0)) {

                    DB::table('job_service_items')->where('job_id', $data['job_id'])->delete();
                } elseif (isset($data['service_id']) && !empty($data['service_id'])) {

                    $serviceData = [
                        'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                        'service_name' => (isset($data['service_name']) && !empty($data['service_name'])) ? $data['service_name'] : '',
                        'base_price' => (isset($data['service_cost']) && !empty($data['service_cost'])) ? $data['service_cost'] : '',
                        'quantity' => (isset($data['service_quantity']) && !empty($data['service_quantity'])) ? $data['service_quantity'] : '',
                        'tax' => $service_tax,
                        'discount' => (isset($data['service_discount']) && !empty($data['service_discount'])) ? $data['service_discount'] : '',
                        'sub_total' => (isset($data['service_total']) && !empty($data['service_total'])) ? $data['service_total'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                if (isset($data['product_id']) && !empty($data['product_id']) && (isset($data['product_quantity']) && !empty($data['product_quantity']) && $data['product_quantity'] == 0)) {

                    DB::table('job_product_items')->where('job_id', $data['job_id'])->delete();
                } elseif (isset($data['product_id']) && !empty($data['product_id'])) {

                    $serviceData = [
                        'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                        'product_name' => (isset($data['product_name']) && !empty($data['product_name'])) ? $data['product_name'] : '',
                        'base_price' => (isset($data['product_cost']) && !empty($data['product_cost'])) ? $data['product_cost'] : '',
                        'quantity' => (isset($data['product_quantity']) && !empty($data['product_quantity'])) ? $data['product_quantity'] : '',
                        'tax' => $product_tax,
                        'discount' => (isset($data['product_discount']) && !empty($data['product_discount'])) ? $data['product_discount'] : '',
                        'sub_total' => (isset($data['product_total']) && !empty($data['product_total'])) ? $data['product_total'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_product_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                $height_slot = $duration / 60;
                $height_slot_px = $height_slot * 80 - 10;

                $returnDate = '<div class="dts mb-1 edit_schedule flexibleslot" data-bs-toggle="modal" data-bs-target="#edit" style="cursor: pointer;height:' . $height_slot_px . 'px;background:' . $technician->color_code . ';" data-id="' . $jobId . '">
                <h5 style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">' . $data['customer_name'] . '</h5>
                <p style="font-size: 11px;"><i class="fas fa-clock"></i>' . $start_date_time->format('h a') . ' -- ' . $jobDetails->job_code . ' <br>' . $jobDetails->job_title . '</p>
                <p style="font-size: 12px;">' . $jobDetails->city . ',' . $jobDetails->state . '</p></div>';

                return ['html' => $returnDate, 'start_date' => $result, 'technician_id' => $data['technician_id']];
            }
        } catch (\Exception $e) {

            Storage::append('UpdateSchedule.log', ' error_msg -- ' . json_encode($e->getMessage()) . ' line number: ' . json_encode($e->getLine()) . ' File: ' . json_encode($e->getFile()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

            return 'false';
        }
    }

    public function autocompletesearchOldJob(Request $request)
    {
        $query = $request->get('query');

        $filterResult = DB::table('jobs')
            ->select('jobs.job_title as name', 'jobs.id', 'users.name as customer')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->where('jobs.id', 'LIKE', '%' . $query . '%')
            ->orWhere('users.name', 'like', '%' . $query . '%')
            ->get();

        return response()->json($filterResult);
    }

    public function getExistingSchedule(Request $request)
    {

        $data = $request->all();

        if (isset($data['jobid']) && !empty($data['jobid'])) {

            $jobDetails = DB::table('jobs')
                ->select(
                    'jobs.id',
                    'jobs.job_title',
                    'jobs.priority',
                    'jobs.description',
                    'jobs.job_code',
                    'jobs.job_type',
                    'jobs.discount',
                    'jobs.gross_total',
                    'jobs.subtotal',
                    'jobs.address_type',
                    'users.name as customername',
                    'users.id as customer_id',
                    'technician.name as technicianname',
                    'technician.id as technician_id',
                    'job_assigned.start_date_time',
                    'job_assigned.duration',
                    'job_assigned.end_date_time',
                    'job_service_items.service_id',
                    'job_service_items.service_name',
                    'job_service_items.base_price as service_cost',
                    'job_service_items.quantity as service_quantity',
                    'job_service_items.discount as service_discount',
                    'job_service_items.sub_total as service_total',
                    'job_service_items.service_description',
                    'job_service_items.tax as service_tax',
                    'job_product_items.product_id',
                    'job_product_items.product_name',
                    'job_product_items.base_price as product_cost',
                    'job_product_items.quantity as product_quantity',
                    'job_product_items.discount as product_discount',
                    'job_product_items.sub_total as product_total',
                    'job_product_items.product_description',
                    'job_product_items.tax as product_tax',
                    'job_notes.note as technician_notes'
                )
                ->join('users', 'users.id', 'jobs.customer_id')
                ->join('users as technician', 'technician.id', 'jobs.technician_id')
                ->leftJoin('job_assigned', 'job_assigned.job_id', 'jobs.id')
                ->leftJoin('job_service_items', 'job_service_items.job_id', 'jobs.id')
                ->leftJoin('job_product_items', 'job_product_items.job_id', 'jobs.id')
                ->leftJoin('job_notes', 'job_notes.job_id', 'jobs.id')
                ->where('jobs.id', $data['jobid'])->first();

            $start_date_time = Carbon::parse($jobDetails->start_date_time);

            $end_date_time = Carbon::parse($jobDetails->end_date_time);

            $jobDetails->start_date_time = $start_date_time->format('Y-m-d\TH:i');

            $jobDetails->end_date_time = $end_date_time->format('Y-m-d\TH:i');

            if (isset($jobDetails->customer_id) && !empty($jobDetails->customer_id)) {
                $getCustomerAddressDetails = DB::table('user_address')->where('user_id', $jobDetails->customer_id)->get()->toArray();
                $jobDetails->address = $getCustomerAddressDetails;
            }

            return $jobDetails;
        }
    }

    public function pending_jobs(Request $request)
    {

        $jobId = $request->job_id;

        $job = JobModel::with('jobDetails', 'JobAssign', 'JobNote', 'jobserviceinfo', 'jobproductinfo', 'technician', 'user')
            ->where('id', $jobId)->first();

        return response()->json($job);
    }

    public function get_by_number(Request $request)
    {
        // dd($request->all());

        $phone = $request->phone;

        $customers = '';

        if (isset($phone) && !empty($phone)) {

            $filterCustomer = User::where('mobile', $phone)
                ->where('role', 'customer')
                ->get();


            if (isset($filterCustomer) && !empty($filterCustomer->count())) {

                foreach ($filterCustomer as $key => $value) {

                    $getCustomerAddress = DB::table('user_address')
                        ->select('user_address.city', 'location_states.state_name', 'user_address.zipcode')
                        ->join('location_states', 'location_states.state_id', 'user_address.state_id')
                        ->where('user_id', $value->id)
                        ->first();

                    $imagePath = public_path('images/customer/' . $value->user_image);

                    if (file_exists($imagePath) && !empty($value->user_image)) {
                        $imageSrc = asset('public/images/customer') . '/' . $value->user_image;
                    } else {
                        $imageSrc = asset('public/images/login_img_bydefault.png');
                    }

                    $customers .= '<h5 class="font-weight-medium mb-2">Select Customer
                                    </h5><div class="customer_sr_box selectCustomer2 px-0" data-id="' . $value->id . '" data-name="' . $value->name . '"><div class="row justify-content-around"><div class="col-md-2 d-flex align-items-center"><span>';
                    $customers .= '<img src="' . $imageSrc . '" alt="user" class="rounded-circle" width="50">';
                    $customers .= '</span></div><div class="col-md-8"><h6 class="font-weight-medium mb-0">' . $value->name . ' ';
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city)) {
                        $customers .= '<small class="text-muted">' . $getCustomerAddress->city . ' Area</small>';
                    }
                    $customers .= '</h6><p class="text-muted test">' . $value->mobile . ' / ' . $value->email . '';
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city) && isset($getCustomerAddress->state_name) && !empty($getCustomerAddress->state_name) && isset($getCustomerAddress->zipcode) && !empty($getCustomerAddress->zipcode)) {
                        $customers .= '<br />' . $getCustomerAddress->city . ', ' . $getCustomerAddress->state_name . ', ' . $getCustomerAddress->zipcode . '';
                    }
                    $customers .= '</p></div></div></div>';
                }
            }
        }

        return ['customers' => $customers];
    }

    public function update(Request $request)
    {

        $data = $request->all();


        if (isset($data) && !empty($data)) {

            if (isset($data['job_id']) && !empty($data['job_id'])) {


                $duration = (int) $data['duration'];


                $technician = User::where('id', $data['technician_id'])->first();

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $getCustomerDetails = User::with('userAddress')
                    ->where('id', $data['customer_id'])
                    ->first();

                $jobsData = [
                    'job_code' => (isset($data['job_code']) && !empty($data['job_code'])) ? $data['job_code'] : '',
                    'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'appliances_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : '',
                    'description' => (isset($data['job_description']) && !empty($data['job_description'])) ? $data['job_description'] : '',
                    'priority' => (isset($data['priority']) && !empty($data['priority'])) ? $data['priority'] : '',
                    'warranty_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
                    'tax' => $service_tax + $product_tax,
                    'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                    'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                    'subtotal' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                    'status' => (isset($data['status']) && !empty($data['status']) && $data['status'] == 'on') ? 'closed' : 'open',
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobId = DB::table('jobs')->where('id', $data['job_id'])->update($jobsData);

                $jobNotes = [
                    'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobNotesID = DB::table('job_notes')->where('job_id', $data['job_id'])->update($jobNotes);

                $JobAssignedData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                    'assign_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'assign_description' => (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '',
                    'duration' => (isset($data['duration']) && !empty($data['duration'])) ? $data['duration'] : '',
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'technician_note_id' => $jobNotesID
                ];

                $jobAssignedID = DB::table('job_assigned')->where('job_id', $data['job_id'])->update($JobAssignedData);

                if (isset($data['service_id']) && !empty($data['service_id'])) {

                    $serviceData = [
                        'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                if (isset($data['product_id']) && !empty($data['product_id'])) {

                    $productData = [
                        'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                    ];

                    $productDataInsert = DB::table('job_product_items')->where('job_id', $data['job_id'])->update($productData);
                }

                if ($request->hasFile('photos')) {

                    $fileData = [];

                    foreach ($request->file('photos') as $file) {

                        $fileName = $data['job_id'] . '_' . $file->getClientOriginalName();

                        $path = 'schedule';

                        $file->storeAs($path, $fileName);

                        $fileData[] = [
                            'job_id' => $data['job_id'],
                            'user_id' => auth()->id(),
                            'path' => $path . '/',
                            'filename' => $fileName,
                            'type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }

                    $fileDataInsert = DB::table('job_files')->insert($fileData);
                }

                return response()->json([
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function store_event(Request $request)
    {
        $auth = Auth::user()->id;
        $date1 = new \DateTime($request->start_date);
        $date2 = new \DateTime($request->end_date);

        $dayOfWeek1 = $date1->format('l');
        $dayOfWeek2 = $date2->format('l');

        $starthours = BusinessHours::where('day', $dayOfWeek1)->first();
        $endhours = BusinessHours::where('day', $dayOfWeek2)->first();

        if ($request->event_type == 'full') {

            $event = new Event();

            $event->technician_id = $request->event_technician_id;
            $event->event_name = $request->event_name;
            $event->event_description = $request->event_description ?? null;
            $event->event_location = $request->event_location ?? null;

            // Concatenate date and time values and format them properly
            $startDateTime = date('Y-m-d H:i:s', strtotime($request->start_date . ' ' . $starthours->start_time));
            $endDateTime = date('Y-m-d H:i:s', strtotime($request->end_date . ' ' . $endhours->end_time));

            // Assign concatenated values to the start_date_time and end_date_time fields
            $event->start_date_time = $startDateTime;
            $event->end_date_time = $endDateTime;

            $event->added_by = $auth;
            $event->updated_by = $auth;
            $event->event_type = $request->event_type;

            $event->save();

            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);

            // Loop through the date range
            $current_date = $start_date->copy();
            while ($current_date->lte($end_date)) {
                // Get the day of the week
                $day_of_week = $current_date->format('l');

                // Retrieve the business hours for the current day
                $business_hours = BusinessHours::where('day', $day_of_week)->first();

                if ($business_hours) {
                    // Create a new schedule entry for this day
                    $schedule = new Schedule();
                    $schedule->event_id = $event->id;
                    $schedule->schedule_type = $request->scheduleType ?? 'default';

                    // Set start and end times based on business hours
                    $schedule_start_time = $current_date->toDateString() . ' ' . $business_hours->start_time;
                    $schedule_end_time = $current_date->toDateString() . ' ' . $business_hours->end_time;

                    $schedule->start_date_time = Carbon::parse($schedule_start_time)->toDateTimeString();
                    $schedule->end_date_time = Carbon::parse($schedule_end_time)->toDateTimeString();

                    // Assign other details
                    $schedule->technician_id = $request->event_technician_id;
                    $schedule->added_by = Auth::user()->id;
                    $schedule->updated_by = Auth::user()->id;

                    // Save the schedule
                    $schedule->save();
                }

                // Move to the next day
                $current_date->addDay();
            }
        } else {

            $event = new Event();

            $event->technician_id = $request->event_technician_id;
            $event->event_name = $request->event_name;
            $event->event_description = $request->event_description ?? null;
            $event->event_location = $request->event_location ?? null;

            // Concatenate date and time values and format them properly
            $startDateTime = date('Y-m-d H:i:s', strtotime($request->start_date . ' ' . $request->start_time));
            $endDateTime = date('Y-m-d H:i:s', strtotime($request->start_date . ' ' . $request->end_time));

            // Assign concatenated values to the start_date_time and end_date_time fields
            $event->start_date_time = $startDateTime;
            $event->end_date_time = $endDateTime;

            $event->added_by = $auth;
            $event->updated_by = $auth;
            $event->event_type = $request->event_type;

            $event->save();

            if ($request->scheduleType) {
                $schedule = new Schedule();

                $schedule->schedule_type = $request->scheduleType;
                $schedule->event_id = $event->id;
                $schedule->start_date_time = $startDateTime;
                $schedule->end_date_time = $endDateTime;
                $schedule->technician_id = $request->event_technician_id;
                $schedule->added_by = auth()->user()->id;
                $schedule->updated_by = auth()->user()->id;

                $schedule->save();
            }
        }


        return response()->json([
            'success' => true,
        ]);
    }

    public function usertax(Request $request)
    {
        $customer = CustomerUserAddress::with('locationStateName')->where('user_id', $request->customerId)->first();
        $statecode = $customer->locationStateName;
        return response()->json($statecode);
    }

    public function schedule_new_customer(Request $request)
    {
        $locationStates = LocationState::all();
        $leadSources = SiteLeadSource::all();

        $site = SiteTags::all(); // Fetch all tags

        return view('schedule.new_customer', compact('locationStates', 'leadSources', 'site'));
    }

    public function userstate(Request $request)
    {

        $a = CustomerUserAddress::where('user_id', $request->technicianId)->with('locationStateName')->first();
        $address = $a->locationStateName;

        $job = User::where('name', $request->technician_name)->first();

        $service_area_ids = explode(',', $job->service_areas);

        $area_locations = LocationServiceArea::whereIn('area_id', $service_area_ids)->get();

        $results = [];

        $area_locations->each(function ($location) use (&$results) {
            $areaName = strtolower(trim($location->area_name)); // Normalize the area name

            switch ($areaName) {
                case 'dallas':
                    $results[] = 44;
                    break;
                case 'new york':
                    $results[] = 35;
                    break;
                case 'atlanta':
                    $results[] = 11;
                    break;
                case 'los angeles':
                    $results[] = 5;
                    break;
                case 'las vegas':
                    $results[] = 34;
                    break;
                case 'miami':
                    $results[] = 10;
                    break;
            }
        });

        $result_string = implode(', ', $results);



        return response()->json([
            'address' => $address,
            'result' => $result_string,
        ]);
    }
    public function new_appliance(Request $request)
    {
        $appliance = new AppliancesType();
        $appliance->appliance_name = $request->appliance;
        $appliance->save();

        // Retrieve all appliances and return them as JSON
        $appliances = AppliancesType::all();
        return response()->json($appliances);
    }

    public function new_manufacturer(Request $request)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->manufacturer_name = $request->manufacturer;
        $manufacturer->save();

        // Retrieve all appliances and return them as JSON
        $manufacturer = Manufacturer::all();
        return response()->json($manufacturer);
    }

    public function service_product(Request $request)
    {
        $newservices = Service::find($request->newservices);
        $newproducts = Products::find($request->newproducts);

        return response()->json([
            'newservices' => $newservices,
            'newproducts' => $newproducts,
        ]);
    }

    public function travel_time(Request $request)
    {
        $user = auth()->user();
        $timezone = User::where('id', $user->id)->first();
        $timezoneTable = TimeZone::where('timezone_id', $timezone->timezone_id)->first();
        $timezoneName = $timezoneTable->timezone_name;
        $currentTime = Carbon::now($timezoneName);

        $tech_add = CustomerUserAddress::where('user_id', $request->tech_id)->first();

        $technician = Schedule::where('technician_id', $request->tech_id)
            ->where('start_date_time', '<=', $currentTime)
            ->where('end_date_time', '>=', $currentTime)
            ->first(); // Find the technician whose working time includes the current time

        if ($technician) {
            $jobAddress = JobModel::where('id', $technician->job_id)->first();
            $address = $jobAddress->latitude . ',' . $jobAddress->longitude;
        } else {

            $address = $tech_add->latitude . ',' . $tech_add->longitude;
        }

        $origin = $address;
        $destination = $request->customer_add;

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'destinations' => $destination,
            'origins' => $origin,
            'key' => 'AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo',
        ]);

        $data = $response->json();
        if ($response->successful()) {
            if ($data['status'] === 'OK' && isset($data['rows'][0]['elements'][0]['duration'])) {
                // Extract duration
                $travelTime = $data['rows'][0]['elements'][0]['duration']['text'];
                return response()->json(['travel_time' => $travelTime]);
            }
        } else {
            return response()->json(['travel_time' => 'Unable to calculate travel time.']);
        }
    }

    public function get_tech_state(Request $request)
    {
        $state_ids = explode(',', $request->stateIds);

        $state_ids = array_map('trim', $state_ids); // Trim any extra spaces
        $state_ids = array_map('intval', $state_ids); // Convert to integers

        // Use whereIn with the properly formatted array
        $state_names = LocationState::whereIn('state_id', $state_ids)->pluck('state_name');

        // Join state names into a single string separated by commas
        $result_string = implode(', ', $state_names->toArray());

        return response()->json($result_string);
    }

    public function customer_appliances(Request $request)
    {
        $appliance = UserAppliances::with('appliance', 'manufacturer')->where('user_id', $request->id)->get();

        return response()->json($appliance);
    }
    public function technician_schedule(Request $request)
    {
        $tech_id = $request->input('tech_id');
        $date = $request->input('date'); // Date part (e.g., '2024-04-26')
        $start_time = $request->input('start_time'); // Time part (e.g., '09:00:00 AM')
        $duration = (int) $request->input('duration'); // Duration in minutes
        $parsedDate = Carbon::parse($date)->format('Y-m-d');
        $start_hours = (int) $request->input('start_hours');
        $end_hours = (int) $request->input('end_hours');
        $business_end_time = Carbon::parse($parsedDate)->setTime($end_hours, 0, 0);

        try {
            $startDateTime = Carbon::parse("$parsedDate $start_time"); // Start time with date
        } catch (Carbon\Exceptions\InvalidFormatException $e) {
            return response()->json(['error' => 'Invalid date-time format'], 400);
        }

        // Add duration to get end time
        $endDateTime = $startDateTime->copy()->addMinutes($duration); // End time after adding duration

        $endDateTime = $startDateTime->copy()->addMinutes($duration); // end time with added duration

        // Check if endDateTime exceeds business end time
        if ($endDateTime->gt($business_end_time)) {
            return response()->json([
                'available' => false,
                'message' => 'Time slot exceeds business hours.',
            ]);
        }

        // Query to check for overlapping schedules
        $overlapCheck = Schedule::where('technician_id', $tech_id)
            ->where(function ($query) use ($startDateTime, $endDateTime) {
                // Check if any existing schedule overlaps with the new slot
                $query->whereBetween('start_date_time', [$startDateTime, $endDateTime])
                    ->orWhereBetween('end_date_time', [$startDateTime, $endDateTime])
                    ->orWhere(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_date_time', '<=', $startDateTime)
                        ->where('end_date_time', '>=', $endDateTime);
                });
            })
            ->get();

        if ($overlapCheck->isNotEmpty()) {
            return response()->json(['available' => false, 'message' => 'Time slot is not available due to overlap.']);
        }

        return response()->json(['available' => true, 'message' => 'Time slot is available.']);
    }

    public function checkSerialNumber(Request $request)
    {
        $serialNumber = $request->input('serial_number');
        $jobDetails = UserAppliances::with('user')->where('serial_number', $serialNumber)->get();

        if ($jobDetails->count() > 0) {
            return response()->json(['status' => 'success', 'data' => $jobDetails]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Serial number not found.']);
        }
    }

    public function get_techName(Request $request)
    {
        $techId = $request->input('techId');
        $user = User::with('TimeZone')->where('id', $techId)->first();

        if ($user->count() > 0) {
            return response()->json($user);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Technician not found']);
        }
    }


    public function drag_update(Request $request)
    {
        $jobId = $request->jobId;
        $techId = $request->techId;
        $duration = $request->duration;
        $start_time = $request->time;
        $date = $request->dragDate;
        $dragNewDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $time_interval = Session::get('time_interval');
        $timezone_name = Session::get('timezone_name');

        $old_tech = Schedule::where('job_id', $jobId)->first();

        try {
            // Update Schedule
            $schedule = Schedule::where('job_id', $jobId)->first();
            if ($schedule) {
                $newFormattedDateTime = Carbon::parse($dragNewDate)->setTimeFromTimeString($start_time);
                $start = Carbon::parse($newFormattedDateTime)->subHours($time_interval);
                $end = $start->copy()->addMinutes($duration);

                $schedule->technician_id = $techId;
                $schedule->start_date_time = $start->toDateTimeString();
                $schedule->end_date_time = $end->toDateTimeString();

                $schedule->save();
            }

            // Update Job
            $job = JobModel::where('id', $jobId)->first();
            if ($job) {
                $job->technician_id = $techId;
                $job->save();
            }

            // Update Job tech event
            $jobTechEvent = JobTechEvent::where('job_id', $jobId)->first();
            if ($jobTechEvent) {

                $newFormattedDateTime = Carbon::parse($dragNewDate)->setTimeFromTimeString($start_time);
                $start = Carbon::parse($newFormattedDateTime)->subHours($time_interval);

                $jobTechEvent->job_schedule = $start->toDateTimeString();
                $jobTechEvent->save();
            }

            $now = Carbon::now($timezone_name);
            $formattedDate = $start->format('D, M j');
            $formattedTime = $now->format('g:ia');
            $formattedDateTime = "{$formattedDate} at {$formattedTime}";

            $activity = 'Job re-scheduled for ' . $formattedDateTime;
            app('JobActivityManager')->addJobActivity($jobId, $activity);
            app('sendNotices')(
                "New Job",
                "Job #{$jobId} moved from {$old_tech->technician->name} and assigned to {$schedule->technician->name}",
                url()->current(),
                'job'
            );
            app('sendNoticesapp')(
                "Job started",
                "Job #{$jobId} moved from {$old_tech->technician->name} and assigned to {$schedule->technician->name}",
                url()->current(),
                'job',
                $techId,
                $jobId
            );


            // Update JobAssign

            $jobAssigned = JobAssign::where('job_id', $jobId)->where('assign_status', 'active')->first();
            if ($jobAssigned) {
                // Update the original job's status to 'moved'
                $jobAssigned->assign_status = 'moved';
                $jobAssigned->save();

                // Copy the updated job and change the status to 'active'
                $newJobAssign = $jobAssigned->replicate();
                $newJobAssign->assign_status = 'active';

                // Update the new job's values
                $newFormattedDateTime = Carbon::parse($jobAssigned->start_date_time)->setTimeFromTimeString($start_time);
                $start = Carbon::parse($newFormattedDateTime)->subHours($time_interval);
                $end = $start->copy()->addMinutes($duration);

                $newJobAssign->technician_id = $techId;
                $newJobAssign->start_date_time = $start->toDateTimeString();
                $newJobAssign->end_date_time = $end->toDateTimeString();
                $newJobAssign->save();
            }


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    public function getMarkerDetailSchedule(Request $request)
    {
        $data = $request->all();

        $result = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.description',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail',
                'jobs.job_field_ids'
            )
            ->join('jobs', 'jobs.id', 'job_assigned.job_id')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
            ->where('job_assigned.assign_status', 'active')
            ->where('job_assigned.job_id', $data['id'])->first();

        $jobFieldsIds = explode(',', $result->job_field_ids);

        $jobFields = DB::table('site_job_fields')
            ->whereIn('field_id', $jobFieldsIds)
            ->get();

        $show_status = '';

        foreach ($jobFields as $item) {
            $show_status .= '<span class="mb-1 badge bg-success">' . $item->field_name . '</span> ';
        }
        if (empty($jobFields)) {
            $show_status = '<span class="mb-1 badge bg-secondary">No Fields</span>';
        }
       $fullAddress = $result
            ? "{$result->address}, {$result->city}, {$result->state}, {$result->zipcode}"
            : 'Address not available';

        $content =  '
            <div class="pp_jobmodel" style="width: 250px;">
                <h5 class="uppercase text-truncate pb-0 mb-0">#' . $result->job_id . ' - ' . $result->subject . '</h5>
                <p class="text-truncate pb-0 mb-0 ft13">' . $result->description . '</p>
                <div class="pp_job_date text-primary">
                ' . Carbon::parse($result->start_date_time)->format('M j Y g:i A') . ' - ' . Carbon::parse($result->end_date_time)->format('g:i A') . '
                </div>
                <p class="ft13 uppercase mb-0 text-truncate">
                    <strong><i class="ri-user-line"></i> ' . $result->name . '</strong>
                </p>
                <div class="ft12"><i class="ri-map-pin-fill"></i> ' . $fullAddress . '</div>
 			<div class="mt-2"><a href="tickets/' . $result->job_id . '" target="_blank" class="btn btn-success waves-effect waves-light btn-sm btn-info">View</a> </div>
            </div>
        ';

        return response()->json(['content' => $content]);
    }

    public function update_job_duration(Request $request)
    {

        $duration = (int) $request->duration;

        // Find the active job assignment
        $job = JobAssign::where('job_id', $request->jobId)
            ->where('assign_status', 'active')
            ->firstOrFail();

        // Calculate the new end date time
        $start_date_time = Carbon::parse($job->start_date_time);
        $end_date_time = $start_date_time->copy()->addMinutes($duration);

        // Update the job
        $job->duration = $duration;
        $job->end_date_time = $end_date_time;
        $job->save();

        // Find and update the corresponding schedule
        $schedule = Schedule::where('job_id', $request->jobId)->firstOrFail();
        $schedule->end_date_time = $start_date_time->copy()->addMinutes($duration);
        $schedule->save();

        // Return a response as needed
        return response()->json(['message' => 'Duration updated successfully']);
    }

    public function schedule_date_screen1(Request $request)
    {
        // Initial setup
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 30;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck !== true) {
            return $permissionCheck;
        }

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $users = User::all();
        $roles = Role::all();
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all(); // Fetch all tags
        $cities = LocationCity::all();
        $cities1 = LocationCity::all();

        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);
        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('D, F j, Y');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');
        $filterDate = $currentDate->format('Y-m-d');
        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');

        $user_array = [];
        $user_data_array = [];
        $assignment_arr = [];
        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();
        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {
            foreach ($user_array as $value) {
                // For job assignments
                $assignmentResults = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'schedule.schedule_type',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('schedule', 'schedule.job_id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                // Attach JobModel to assignmentResults
                foreach ($assignmentResults as $assignment) {
                    $assignment->JobModel = JobModel::with('user', 'technician', 'jobassignname', 'addresscustomer')->find($assignment->job_id);
                    $datetimeString = $assignment->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $assignment_arr[$value][$time][] = $assignment;
                }

                // For schedules
                $scheduleResults = Schedule::with('JobModel', 'technician')
                    ->where('show_on_schedule', 'yes')
                    ->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                foreach ($scheduleResults as $schedule) {
                    $datetimeString = $schedule->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $schedule_arr[$value][$time][] = $schedule;
                }
            }
        }

        $current_time = Carbon::now($timezone_name)->format('h:i A');
        $new_currentDate = $currentDate->format('Y-m-d');

        $data = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', '=', $new_currentDate)
            ->where('job_assigned.assign_status', 'active')
            ->orderBy('job_assigned.pending_number', 'asc')
            ->get();



        $screen2 = view('schedule.scheduleCalender1', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr', 'hours', 'current_time', 'data'))->render();

        return response()->json(['tbody' => $screen2]);
    }
    public function schedule_date_screen2(Request $request)
    {
        // Initial setup
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 30;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck !== true) {
            return $permissionCheck;
        }

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $users = User::all();
        $roles = Role::all();
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all(); // Fetch all tags
        $cities = LocationCity::all();
        $cities1 = LocationCity::all();

        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);
        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('D, F j, Y');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');
        $filterDate = $currentDate->format('Y-m-d');
        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');

        $user_array = [];
        $user_data_array = [];
        $assignment_arr = [];
        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();
        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {
            foreach ($user_array as $value) {
                // For job assignments
                $assignmentResults = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'schedule.schedule_type',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('schedule', 'schedule.job_id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                // Attach JobModel to assignmentResults
                foreach ($assignmentResults as $assignment) {
                    $assignment->JobModel = JobModel::with('user', 'technician', 'jobassignname', 'addresscustomer')->find($assignment->job_id);
                    $datetimeString = $assignment->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $assignment_arr[$value][$time][] = $assignment;
                }

                // For schedules
                $scheduleResults = Schedule::with('JobModel', 'technician')
                    ->where('show_on_schedule', 'yes')
                    ->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                foreach ($scheduleResults as $schedule) {
                    $datetimeString = $schedule->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $schedule_arr[$value][$time][] = $schedule;
                }
            }
        }

        $current_time = Carbon::now($timezone_name)->format('h:i A');
        $new_currentDate = $currentDate->format('Y-m-d');

        $data = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', '=', $new_currentDate)
            ->where('job_assigned.assign_status', 'active')
            ->orderBy('job_assigned.pending_number', 'asc')
            ->get();



        $screen2 = view('schedule.scheduleCalender2', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr', 'hours', 'current_time', 'data'))->render();

        return response()->json(['tbody' => $screen2]);
    }
    public function schedule_date_screen3(Request $request)
    {
        // Initial setup
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 30;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck !== true) {
            return $permissionCheck;
        }

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $users = User::all();
        $roles = Role::all();
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all(); // Fetch all tags
        $cities = LocationCity::all();
        $cities1 = LocationCity::all();

        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);
        $currentDay = $currentDate->format('l');
        $currentDayLower = strtolower($currentDay);
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('D, F j, Y');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');
        $filterDate = $currentDate->format('Y-m-d');
        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');

        $user_array = [];
        $user_data_array = [];
        $assignment_arr = [];
        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();
        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {
            foreach ($user_array as $value) {
                // For job assignments
                $assignmentResults = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'schedule.schedule_type',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('schedule', 'schedule.job_id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                // Attach JobModel to assignmentResults
                foreach ($assignmentResults as $assignment) {
                    $assignment->JobModel = JobModel::with('user', 'technician', 'jobassignname', 'addresscustomer')->find($assignment->job_id);
                    $datetimeString = $assignment->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $assignment_arr[$value][$time][] = $assignment;
                }

                // For schedules
                $scheduleResults = Schedule::with('JobModel', 'technician')
                    ->where('show_on_schedule', 'yes')
                    ->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                foreach ($scheduleResults as $schedule) {
                    $datetimeString = $schedule->start_date_time;
                    $newFormattedDateTime = Carbon::parse($datetimeString)->addHours($time_interval)->format('Y-m-d H:i:s');
                    $time = date("h:i A", strtotime($newFormattedDateTime));
                    $schedule_arr[$value][$time][] = $schedule;
                }
            }
        }

        $current_time = Carbon::now($timezone_name)->format('h:i A');
        $new_currentDate = $currentDate->format('Y-m-d');

        $data = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', '=', $new_currentDate)
            ->where('job_assigned.assign_status', 'active')
            ->orderBy('job_assigned.pending_number', 'asc')
            ->get();



        $screen2 = view('schedule.scheduleCalender3', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr', 'hours', 'current_time', 'data'))->render();

        return response()->json(['tbody' => $screen2]);
    }
    public function getJobsByDate(Request $request)
    {
        $new_currentDate = \Carbon\Carbon::parse($request->date)->format('Y-m-d');

        // dd($new_currentDate);
        $data = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->where('job_assigned.assign_status', 'active')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', '=', $new_currentDate)
            ->orderBy('job_assigned.pending_number', 'asc')
            ->get();



        return response()->json(['data' => $data]);
    }

   
       public function demo(Request $request)
    {

        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);
        $filterDate = $currentDate->format('Y-m-d');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');
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




        //merger routing here

        $userTimezone = Session::get('user_timezone', 'UTC');
        $currentDate = Carbon::now($userTimezone)->format('Y-m-d');

        // Fetch active technicians
        $activeTechnicians = User::where('role', 'technician')->where('status', 'active')->get();

        if ($activeTechnicians->isEmpty()) {
            return view('jobrouting.technicians_jobs_map')->with('technicians', []);
        }

        $technicianResponseData = [];

        foreach ($activeTechnicians as $singleTechnician) {
            $technicianLocationData = CustomerUserAddress::where('user_id', $singleTechnician->id)
                ->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

            if (!$technicianLocationData) {
                $technicianResponseData[] = [
                    'technician' => [
                        'id' => $singleTechnician->id,
                        'name' => $singleTechnician->name,
                        'error' => 'Technician location not found.',
                    ],
                ];
                continue;
            }

            $technicianLocationData->full_address = "{$technicianLocationData->address_line1}, {$technicianLocationData->city}, {$technicianLocationData->state_name} {$technicianLocationData->zipcode}";

            $technicianJobs = Schedule::where('technician_id', $singleTechnician->id)
                ->whereDate('start_date_time', $currentDate)
                ->get(['job_id', 'position', 'is_routes_map', 'job_onmap_reaching_timing']);

            $technicianRouteData = RoutingJob::where('user_id', $singleTechnician->id)->first();

            $individualTechnicianData = [
                'technician' => [
                    'id' => $singleTechnician->id,
                    'name' => $singleTechnician->name,
                    'full_address' => $technicianLocationData->full_address,
                    'latitude' => $technicianLocationData->latitude,
                    'longitude' => $technicianLocationData->longitude,
                ],
                'jobs' => [],
            ];

            if ($technicianJobs->isEmpty()) {
                $individualTechnicianData['error'] = 'No jobs found for this technician on the selected date.';
                $technicianResponseData[] = $individualTechnicianData;
                continue;
            }

            if ($technicianRouteData && !empty($technicianRouteData->best_route)) {
                $routeJobIds = explode(',', $technicianRouteData->best_route);

                $filteredTechnicianJobs = $technicianJobs->whereIn('job_id', $routeJobIds);

                foreach ($filteredTechnicianJobs as $technicianJob) {
                    $relatedCustomerIds = JobAssign::where('job_id', $technicianJob->job_id)->pluck('customer_id');
                    $customerAddresses = CustomerUserAddress::whereIn('user_id', $relatedCustomerIds)
                        ->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

                    foreach ($customerAddresses as $customerAddress) {
                        $customerName = User::where('id', $customerAddress->user_id)->value('name');
                        $customerAddress->full_address = "{$customerAddress->address_line1}, {$customerAddress->city}, {$customerAddress->state_name} {$customerAddress->zipcode}";

                        $individualTechnicianData['jobs'][] = [
                            'job_id' => $technicianJob->job_id,
                            'position' => $technicianJob->position,
                            'is_routes_map' => $technicianJob->is_routes_map,
                            'job_onmap_reaching_timing' => $technicianJob->job_onmap_reaching_timing,
                            'customer' => [
                                'id' => $customerAddress->user_id,
                                'name' => $customerName,
                                'full_address' => $customerAddress->full_address,
                                'latitude' => $customerAddress->latitude,
                                'longitude' => $customerAddress->longitude,
                            ],
                        ];
                    }
                }
            }

            $technicianResponseData[] = $individualTechnicianData;
        }

        $responsePayload = json_encode($technicianResponseData);

        $allTechnicians = User::where('role', 'technician')->where('status', 'active')->get();
        $allRoutingTriggers = RoutingTrigger::all();
        $serviceAreaLocations = LocationServiceArea::all();

        $jobQuery = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',

                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->orderBy('job_assigned.pending_number', 'asc');

        if (!empty($currentDate)) {
            $jobQuery->whereDate('start_date_time', $currentDate);
        }

        $jobData = $jobQuery->get();

        $tech = User::where('role', 'technician')->where('status', 'active')->get();


        //merge end here

        // Pass both technicians and schedules to the view
        return view('schedule.demo', compact('TodayDate','technicians', 'schedules', 'formattedDate', 'hours', 'tomorrowDate', 'previousDate', 'jobData', 'responsePayload', 'allTechnicians', 'allRoutingTriggers', 'serviceAreaLocations', 'tech'));
    }
    public function updateJobTechnician(Request $request)
    {
        $duration = $request->duration;
        $start_time = $request->time;
        $date = $request->date;
        $dragNewDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $jobId = $request->input('job_id');
        $technicianId = $request->input('technician_id');
        $time_interval = Session::get('time_interval');
        $timezone_name = Session::get('timezone_name');

        $old_tech = Schedule::where('job_id', $jobId)->first();

        // Validate that both job_id and technician_id are not null
        if (is_null($jobId) || is_null($technicianId)) {
            return response()->json(['success' => false, 'message' => 'Invalid job or technician ID.']);
        }
        try {
            // Update Schedule
            $schedule = Schedule::where('job_id', $jobId)->first();

            // Check if the schedule exists
            if (!$schedule) {
                return response()->json(['success' => false, 'message' => 'Job not found.']);
            }
            if ($schedule) {
                $newFormattedDateTime = Carbon::parse($dragNewDate)->setTimeFromTimeString($start_time);
                $start = Carbon::parse($newFormattedDateTime)->subHours($time_interval);
                $end = $start->copy()->addMinutes($duration);

                $schedule->technician_id = $technicianId;
                $schedule->start_date_time = $start->toDateTimeString();
                $schedule->end_date_time = $end->toDateTimeString();
                $schedule->save();
            }
            // Update Job
            $job = JobModel::where('id', $jobId)->first();
            if ($job) {
                $job->technician_id = $technicianId;
                $job->save();
            }


            // Update Job tech event
            $jobTechEvent = JobTechEvent::where('job_id', $jobId)->first();
            if ($jobTechEvent) {

                $newFormattedDateTime = Carbon::parse($dragNewDate)->setTimeFromTimeString($start_time);
                $start = Carbon::parse($newFormattedDateTime)->subHours($time_interval);

                $jobTechEvent->job_schedule = $start->toDateTimeString();
                $jobTechEvent->save();
            }

            $now = Carbon::now($timezone_name);
            $formattedDate = $start->format('D, M j');
            $formattedTime = $now->format('g:ia');
            $formattedDateTime = "{$formattedDate} at {$formattedTime}";

            $activity = 'Job re-scheduled for ' . $formattedDateTime;
            app('JobActivityManager')->addJobActivity($jobId, $activity);
            app('sendNotices')(
                "New Job",
                "Job #{$jobId} moved from {$old_tech->technician->name} and assigned to {$schedule->technician->name}",
                url()->current(),
                'job'
            );
            app('sendNoticesapp')(
                "Job started",
                "Job #{$jobId} moved from {$old_tech->technician->name} and assigned to {$schedule->technician->name}",
                url()->current(),
                'job',
                $technicianId,
                $jobId
            );


            // Update JobAssign

            $jobAssigned = JobAssign::where('job_id', $jobId)->where('assign_status', 'active')->first();
            if ($jobAssigned) {
                // Update the original job's status to 'moved'
                $jobAssigned->assign_status = 'moved';
                $jobAssigned->save();

                // Copy the updated job and change the status to 'active'
                $newJobAssign = $jobAssigned->replicate();
                $newJobAssign->assign_status = 'active';

                // Update the new job's values
                $newFormattedDateTime = Carbon::parse($jobAssigned->start_date_time)->setTimeFromTimeString($start_time);
                $start = Carbon::parse($newFormattedDateTime)->subHours($time_interval);
                $end = $start->copy()->addMinutes($duration);

                $newJobAssign->technician_id = $technicianId;
                $newJobAssign->start_date_time = $start->toDateTimeString();
                $newJobAssign->end_date_time = $end->toDateTimeString();
                $newJobAssign->save();
            }



            return response()->json(['success' => true, 'message' => 'The job has been updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function demoScheduleupdateiframe(Request $request)
    {
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);

        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');
        if ($request->has('date')) {
            $filterDate = Carbon::parse($request->date)->format('Y-m-d');
        } else {
            $filterDate = $currentDate->format('Y-m-d');
        }

        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $currentDay = Carbon::parse($filterDate)->format('l');
        $currentDayLower = strtolower($currentDay);

        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = Carbon::parse($filterDate)->format('D, F j, Y');

        $technicians = User::where('role', 'technician')->where('status', 'active')->get();
        $schedules = collect();

        foreach ($technicians as $technician) {
            $technicianSchedules = Schedule::where('technician_id', $technician->id)
                ->whereDate('start_date_time', $filterDate)
                ->get();
            $schedules = $schedules->merge($technicianSchedules);
        }

        $schedules->transform(function ($schedule) use ($time_interval) {
            $schedule->start_date_time = Carbon::parse($schedule->start_date_time)
                ->addHours($time_interval)
                ->format('Y-m-d H:i:s');
            return $schedule;
        });

                $tech = User::where('role', 'technician')->where('status', 'active')->get();


        $screen2 = view('schedule.schedule_iframe', compact('TodayDate','technicians', 'schedules', 'formattedDate', 'hours', 'tomorrowDate', 'previousDate','tech'))->render();

        return response()->json(['tbody' => $screen2]);
    }
    public function demoScheduleupdate(Request $request)
    {
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $data = $request->all();
        $currentDate = isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date']) : Carbon::now($timezone_name);

        if ($request->has('date')) {
            $filterDate = Carbon::parse($request->date)->format('Y-m-d');
        } else {
            $filterDate = $currentDate->format('Y-m-d');
        }

        $TodayDate = Carbon::now($timezone_name)->format('Y-m-d');
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $currentDay = Carbon::parse($filterDate)->format('l');
        $currentDayLower = strtolower($currentDay);

        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = Carbon::parse($filterDate)->format('D, F j, Y');

        $technicians = User::where('role', 'technician')->where('status', 'active')->get();
        $schedules = collect();

        foreach ($technicians as $technician) {
            $technicianSchedules = Schedule::where('technician_id', $technician->id)
                ->whereDate('start_date_time', $filterDate)
                ->get();
            $schedules = $schedules->merge($technicianSchedules);
        }

        $schedules->transform(function ($schedule) use ($time_interval) {
            $schedule->start_date_time = Carbon::parse($schedule->start_date_time)
                ->addHours($time_interval)
                ->format('Y-m-d H:i:s');
            return $schedule;
        });

                $tech = User::where('role', 'technician')->where('status', 'active')->get();


        $screen2 = view('schedule.demoSchedule', compact('TodayDate','technicians', 'schedules', 'formattedDate', 'hours', 'tomorrowDate', 'previousDate','tech'))->render();

        return response()->json(['tbody' => $screen2]);
    }
    public function getALlJobDetails(Request $request)
    {
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $currentDate = Carbon::now($timezone_name);
        $filterDate = Carbon::parse($request->date)->format('Y-m-d');

        $schedule = Schedule::with([
            'JobModel' => function ($query) {
                $query->with([
                    'user',
                    'jobproductinfohasmany.product',
                    'jobserviceinfohasmany.service',
                    'addresscustomer',
                    'JobAppliances.Appliances.manufacturer',
                    'JobAppliances.Appliances.appliance',
                    // 'fieldids' 
                ]);
            },
            'technician'
        ])->where('technician_id', $request->tech_id)
            ->where('start_date_time', 'LIKE', "%$filterDate%")
            ->where('show_on_schedule', 'yes')
            ->get();
        // dd($schedule);
        $schedule->each(function ($scheduleItem) {
            $jobModel = $scheduleItem->JobModel;
            if ($jobModel) {
                $jobModel->fieldids = $jobModel->fieldids(); // Manually load the custom fieldids
            }
        });


        return response()->json($schedule);
    }

    public function update_view_job(Request $request, $id)
    {

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        $date = $request->date;
        $from = $request->from_time;
        $to = $request->to_time;

        $start_date = Carbon::parse($date . ' ' . $from);
        $end_date = Carbon::parse($date . ' ' . $to);

        $newFormattedDateTime = Carbon::parse($start_date)->subHours($time_interval)->format('Y-m-d H:i:s');
        $newFormattedDateTime2 = Carbon::parse($end_date)->subHours($time_interval)->format('Y-m-d H:i:s');

        $start_date_time = Carbon::parse($newFormattedDateTime);
        $end_date_time = Carbon::parse($newFormattedDateTime2);

        $job = JobModel::find($id);

        $job->job_title = $request->job_title;
        $job->priority = $request->priority;
        $job->warranty_type = $request->job_type;
        $job->warranty_ticket = $request->warranty_ticket;
        $job->description = $request->job_description;

        $job->save();

        if (isset($request->date) && !empty($request->date)) {
            // Update Schedule

            $schedule = Schedule::where('job_id', $id)->first();

            $schedule->start_date_time = $start_date_time;
            $schedule->end_date_time = $end_date_time;
            $schedule->added_by = auth()->user()->id;
            $schedule->updated_by = auth()->user()->id;

            $schedule->save();

            // Update JobTechEvent

            $job_tech = JobTechEvent::where('job_id', $id)->first();

            $job_tech->job_schedule = $start_date_time;

            $job_tech->save();

            // Update JobAssign

            $jobAssigned = JobAssign::where('job_id', $id)->where('assign_status', 'active')->first();

            $jobAssigned->start_date_time = $start_date_time;
            $jobAssigned->end_date_time = $end_date_time;
            $jobAssigned->save();
        }
        $app_id = JobAppliances::where('job_id', $request->id)->first();

        if (isset($request->exist_appl_id) && !empty($request->exist_appl_id)) {

            $userappl = [
                'appliance_id' => (isset($request->exist_appl_id) && !empty($request->exist_appl_id)) ? $request->exist_appl_id : '',
            ];
            $addAppliancesUser = DB::table('job_appliance')->where('job_id', $request->id)->update($userappl);
        } else {

            if (isset($request->appliances) && !empty($request->appliances) && isset($request->manufacturer) && !empty($request->manufacturer)) {
                $jobDetails = [
                    'appliance_type_id' => (isset($request->appliances) && !empty($request->appliances)) ? $request->appliances : '',
                    'model_number' => (isset($request->model_number) && !empty($request->model_number)) ? $request->model_number : '',
                    'serial_number' => (isset($request->serial_number) && !empty($request->serial_number)) ? $request->serial_number : '',
                    'manufacturer_id' => (isset($request->manufacturer) && !empty($request->manufacturer)) ? $request->manufacturer : '',
                ];

                $userappliances = DB::table('user_appliances')->where('appliance_id', $app_id->appliance_id)->update($jobDetails);
            }

        }
        $interval = Session::get('time_interval');


        $job = JobModel::with('jobassignname', 'JobTechEvent', 'JobAssign', 'usertechnician', 'addedby', 'jobfieldname', 'JobAppliances.Appliances.manufacturer', 'JobAppliances.Appliances.appliance')->find($id);

        $startDateTime = $job->schedule->start_date_time
            ? Carbon::parse($job->schedule->start_date_time)
            : null;

        if ($startDateTime && isset($interval)) {
            // Add the interval to the parsed time
            $startDateTime->addHours($interval);
        }
        $startDateTime2 = $job->schedule->end_date_time
            ? Carbon::parse($job->schedule->end_date_time)
            : null;

        if ($startDateTime2 && isset($interval)) {
            // Add the interval to the parsed time
            $startDateTime2->addHours($interval);
        }

        // Format the date if it exists
        $formattedDateTime = $startDateTime ? $startDateTime->format('jS F Y, h:i A') : null;
        $enr_date = $startDateTime ? $startDateTime->format('M d Y g:i a') : null;

        $newDate = $startDateTime ? $startDateTime->format('jS F Y') : null;
        $fromDate = $startDateTime ? $startDateTime->format('H:i:a') : null;
        $toDate = $startDateTime2 ? $startDateTime2->format('H:i:a') : null;

        return response()->json([
            'job' => $job,
            'startDateTime' => $formattedDateTime,
            'newDate' => $newDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'enr_date' => $enr_date,
        ]);
    }

    public function demoiframe(Request $request)
    {

        $timezone_name = Session::get('timezone_name');
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

        // Pass both technicians and schedules to the view
        return view('schedule.schedule_index_iframe', compact('technicians', 'schedules', 'formattedDate', 'hours', 'tomorrowDate', 'previousDate'));
    }


    public function getLocation(Request $request)
    {
        // Dump the request data for debugging
        //  dd($request->all());

        $userId = $request->input('id'); // Technician's user_id
        $inputDate = Carbon::parse($request->input('date'))->format('Y-m-d');
        $priority = $request->input('priority');

        // Fetch technician's location
        $technicianLocation = CustomerUserAddress::where('user_id', $userId)
            ->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

        if (!$technicianLocation) {
            return response()->json(['error' => 'Technician location not found.'], 404);
        }

        $technicianName = User::where('id', $technicianLocation->user_id)->value('name');
        $technicianLocation->name = $technicianName;
        $technicianLocation->full_address = $technicianLocation->address_line1 . ', ' .
            $technicianLocation->city . ', ' .
            $technicianLocation->state_name . ' ' .
            $technicianLocation->zipcode;

        $currentDate = Carbon::createFromFormat('Y-m-d', $inputDate)->startOfDay();

        // Fetch job IDs, positions, and is_routes_map for the technician on the input date
        $jobs = Schedule::where('technician_id', $userId)
            ->whereDate('start_date_time', '=', $currentDate)
            ->get(['job_id', 'position', 'is_routes_map', 'job_onmap_reaching_timing']);

        if ($jobs->isEmpty()) {
            return response()->json(['error' => 'No jobs found for this technician on the selected date.'], 404);
        }

        $firstJob = $jobs->first();
        $technicianLocation->job_id = $firstJob->job_id;
        $technicianLocation->position = $firstJob->position;
        $technicianLocation->is_routes_map = $firstJob->is_routes_map;

        // Fetch customer IDs assigned to the jobs
        $customerIds = JobAssign::whereIn('job_id', $jobs->pluck('job_id'))->pluck('customer_id');

        if ($customerIds->isEmpty()) {
            return response()->json(['error' => 'No customers found for these jobs.'], 404);
        }

        $customerLocations = CustomerUserAddress::whereIn('user_id', $customerIds)
            ->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

        if ($customerLocations->isEmpty()) {
            return response()->json(['error' => 'No customer locations found.'], 404);
        }

        foreach ($customerLocations as $customerLocation) {
            $customerName = User::where('id', $customerLocation->user_id)->value('name');
            $customerLocation->name = $customerName;

            $job = JobAssign::where('customer_id', $customerLocation->user_id)
                ->whereIn('job_id', $jobs->pluck('job_id'))
                ->first(['job_id', 'duration']);

            if ($job) {
                $customerLocation->job_id = $job->job_id;

                // Format job duration
                $hours = floor($job->duration / 60);
                $minutes = $job->duration % 60;
                $formattedDuration = "{$hours} hr {$minutes} min";
                $customerLocation->duration = $formattedDuration;

                // Fetch position and is_routes_map from Schedule
                $schedule = Schedule::where('technician_id', $userId)
                    ->where('job_id', $job->job_id)
                    ->first(['position', 'is_routes_map', 'job_onmap_reaching_timing']);

                if ($schedule) {
                    $customerLocation->position = $schedule->position;
                    $customerLocation->is_routes_map = $schedule->is_routes_map;
                    $customerLocation->job_onmap_reaching_timing = $schedule->job_onmap_reaching_timing;

                }
            }

            $customerLocation->full_address = $customerLocation->address_line1 . ', ' .
                $customerLocation->city . ', ' .
                $customerLocation->state_name . ' ' .
                $customerLocation->zipcode;
        }

        // Sort customers based on distance from technician's location
        $sortedCustomers = $customerLocations->map(function ($customer) use ($technicianLocation) {
            $distance = $this->calculateDistance(
                $technicianLocation->latitude,
                $technicianLocation->longitude,
                $customer->latitude,
                $customer->longitude
            );
            $customer->distance = $distance;
            return $customer;
        })->sortBy('distance');

        $sortedCustomers = $sortedCustomers->values()->map(function ($customer, $index) {
            $customer->number = $index + 1;
            return $customer;
        });

        // Customers filtered based on is_routes_map = 1
        $sortedCustomers1 = $customerLocations->filter(function ($customer) use ($userId, $jobs) {
            $jobId = JobAssign::where('customer_id', $customer->user_id)
                ->whereIn('job_id', $jobs->pluck('job_id'))
                ->value('job_id');

            return Schedule::where('technician_id', $userId)
                ->where('job_id', $jobId)
                ->where('is_routes_map', 1)
                ->exists();
        })->map(function ($customer) use ($technicianLocation) {
            $distance = $this->calculateDistance(
                $technicianLocation->latitude,
                $technicianLocation->longitude,
                $customer->latitude,
                $customer->longitude
            );
            $customer->distance = $distance;
            return $customer;
        });

        $sortedCustomers1 = $sortedCustomers1->values()->map(function ($customer, $index) {
            $customer->number = $index + 1;
            return $customer;
        });

        // Customers filtered based on is_confirmed = 'yes' in JobModel
        $sortedCustomers2 = $customerLocations->filter(function ($customer) use ($userId, $jobs) {
            $jobId = JobAssign::where('customer_id', $customer->user_id)
                ->whereIn('job_id', $jobs->pluck('job_id'))
                ->value('job_id');

            return Schedule::where('technician_id', $userId)
                ->where('job_id', $jobId)
                ->whereHas('JobModel', function ($query) {
                    $query->where('is_confirmed', 'yes');
                })
                ->exists();
        })->map(function ($customer) use ($technicianLocation) {
            $distance = $this->calculateDistance(
                $technicianLocation->latitude,
                $technicianLocation->longitude,
                $customer->latitude,
                $customer->longitude
            );
            $customer->distance = $distance;
            return $customer;
        });

        $sortedCustomers2 = $sortedCustomers2->values()->map(function ($customer, $index) {
            $customer->number = $index + 1;
            return $customer;
        });

        // Customers filtered based on priority
        $sortedCustomers3 = $customerLocations->filter(function ($customer) use ($userId, $jobs, $priority) {
            $jobId = JobAssign::where('customer_id', $customer->user_id)
                ->whereIn('job_id', $jobs->pluck('job_id'))
                ->value('job_id');

            return Schedule::where('technician_id', $userId)
                ->where('job_id', $jobId)
                ->whereHas('JobModel', function ($query) use ($priority) {
                    $query->where('priority', $priority);
                })
                ->exists();
        })->map(function ($customer) use ($technicianLocation) {
            $distance = $this->calculateDistance(
                $technicianLocation->latitude,
                $technicianLocation->longitude,
                $customer->latitude,
                $customer->longitude
            );
            $customer->distance = $distance;
            return $customer;
        });

        $sortedCustomers3 = $sortedCustomers3->values()->map(function ($customer, $index) {
            $customer->number = $index + 1;
            return $customer;
        });

        return response()->json([
            'technician_location' => $technicianLocation,
            'sorted_customers' => $sortedCustomers,
            'sorted_customers1' => $sortedCustomers1,
            'sorted_customers2' => $sortedCustomers2, // Response for jobs with is_confirmed = 'yes'
            'sorted_customers3' => $sortedCustomers3, // Response for jobs with priority filtering
        ]);
    }






    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }



    public function send_sms_schedule(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'tech_id' => 'required',
        ]);

        $technician = User::find($request->tech_id);

        if (!$technician) {
            return response()->json(['success' => false, 'message' => 'Technician not found.']);
        }

        // Twilio SMS logic
        $messageContent = $request->input('message');
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $fromNumber = env('TWILIO_FROM');
        $receiverNumber = '+917030467187'; // Replace with the actual technician phone number

        try {
            $client = new Client($sid, $token);
            $client->messages->create($receiverNumber, [
                'from' => $fromNumber,
                'body' => $messageContent,
            ]);

            return response()->json(['success' => true, 'message' => 'SMS sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send SMS.']);
        }
    }

    public function getLocationpositionsaveRoute1(Request $request)
    {
        // Get the customers array from the request
        $customers = $request->input('customers');
        $updatedCustomers = [];
        // dd($customers);
        // Parse the input date and set to start of day
        $inputDate = Carbon::parse($request->input('date'))->format('Y-m-d');
        $currentDate = Carbon::createFromFormat('Y-m-d', $inputDate)->startOfDay();

        // Loop through the customers and update each position and is_routes_map in the Schedule model
        foreach ($customers as $customer) {
            $userId = $customer['user_id']; // This is the customer_id
            $position = $customer['position'];
            $technicianId = $customer['technicianId'];
            $jobId = $customer['jobid']; // Get jobid from the customer array
            $isRoute = $customer['isroute']; // Capture isroute value

            // Update the position and is_routes_map in the Schedule model for the respective job
            Schedule::where('job_id', $jobId) // Use job_id from the customer data
                ->where('technician_id', $technicianId) // Ensure we're updating the correct technician's job
                // ->whereDate('start_date_time', '=', $currentDate) // Check date condition
                ->update([
                    'position' => $position,
                    'is_routes_map' => $isRoute // Update is_routes_map based on isroute
                ]);

            // Fetch customer details from CustomerUserAddress and User model
            $customerData = CustomerUserAddress::where('user_id', $userId)->first();
            $customerUser = User::find($userId);

            // Fetch technician details
            $technicianData = CustomerUserAddress::where('user_id', $technicianId)->first();
            $technicianUser = User::find($technicianId);

            // Collect customer and technician data for the response
            $updatedCustomers[] = [
                'customer' => [
                    'user_id' => $customerUser->id,
                    'name' => $customerUser->name,
                    'latitude' => $customerData->latitude,
                    'longitude' => $customerData->longitude,
                    'full_address' => $customerData->address_line1 . ', ' .
                        $customerData->city . ', ' .
                        $customerData->zipcode . ', ' .
                        $customerData->state_name,
                    'position' => $position, // Include the updated position
                    'isroute' => $isRoute
                ],
                'technician' => [
                    'user_id' => $technicianUser->id,
                    'name' => $technicianUser->name,
                    'latitude' => $technicianData->latitude,
                    'longitude' => $technicianData->longitude,
                    'full_address' => $technicianData->address_line1 . ', ' .
                        $technicianData->city . ', ' .
                        $technicianData->zipcode . ', ' .
                        $technicianData->state_name,
                    'position' => $position, // Include the updated position for technician
                    'isroute' => $isRoute

                ]
            ];
        }

        // Return success response with updated customer and technician data
        return response()->json([
            'success' => true,
            'message' => 'Positions updated successfully',
            'updated_customers' => $updatedCustomers
        ]);
    }



    // public function getLocationpositionsaveRoute(Request $request)
// {  
//     // Get the customers array from the request
//     $customers = $request->input('customers');
//     $updatedCustomers = [];
//     $previousLatitude = null; // Store previous latitude for distance calculation
//     $previousLongitude = null; // Store previous longitude for distance calculation
//     $speedKmPerHour = 50; // Assuming technician travels at 50 km/h, adjust this value based on your requirements

    //     // Parse the input date and set to start of day
//     $inputDate = Carbon::parse($request->input('date'))->format('Y-m-d');
//     $currentDate = Carbon::createFromFormat('Y-m-d', $inputDate)->startOfDay();

    //     // Loop through the customers and update each position and is_routes_map in the Schedule model
//     foreach ($customers as $customer) {
//         $userId = $customer['user_id']; // This is the customer_id
//         $position = $customer['position'];
//         $technicianId = $customer['technicianId'];
//         $jobId = $customer['jobid']; // Get jobid from the customer array
//         $isRoute = $customer['isroute']; // Capture isroute value

    //         // Initialize timing variables
//         $calculatedTimingInMinutes = 0; // To store the calculated time in minutes

    //         // Fetch customer details from CustomerUserAddress and User model
//         $customerData = CustomerUserAddress::where('user_id', $userId)->first();
//         $customerUser = User::find($userId);

    //         // Fetch technician details
//         $technicianData = CustomerUserAddress::where('user_id', $technicianId)->first();
//         $technicianUser = User::find($technicianId);

    //         // Only calculate timing between points if isRoute is 1 and previous position exists
//         if ($isRoute == 1 && $previousLatitude !== null && $previousLongitude !== null) {
//             // Calculate the distance between previous and current positions using Haversine formula
//             $distanceKm = $this->calculateDistance($previousLatitude, $previousLongitude, $customerData->latitude, $customerData->longitude);

    //             // Calculate time in hours (Distance / Speed)
//             $calculatedTimeInHours = $distanceKm / $speedKmPerHour;

    //             // Convert time to minutes
//             $calculatedTimingInMinutes = $calculatedTimeInHours * 60; // Time in minutes
//         }

    //         // Update the position, is_routes_map, and job_onmap_reaching_timing in the Schedule model for the respective job
//         Schedule::where('job_id', $jobId) // Use job_id from the customer data
//             ->where('technician_id', $technicianId) // Ensure we're updating the correct technician's job
//             ->update([
//                 'position' => $position,
//                 'is_routes_map' => $isRoute, // Update is_routes_map based on isroute
//                 'job_onmap_reaching_timing' => round($calculatedTimingInMinutes, 2) // Store calculated timing between two positions
//             ]);

    //         // Update the previous position and coordinates for the next loop iteration
//         $previousLatitude = $customerData->latitude;
//         $previousLongitude = $customerData->longitude;

    //         // Collect customer and technician data for the response
//         $updatedCustomers[] = [
//             'customer' => [
//                 'user_id' => $customerUser->id,
//                 'name' => $customerUser->name,
//                 'latitude' => $customerData->latitude,
//                 'longitude' => $customerData->longitude,
//                 'full_address' => $customerData->address_line1 . ', ' . 
//                                  $customerData->city . ', ' . 
//                                  $customerData->zipcode . ', ' . 
//                                  $customerData->state_name,
//                 'position' => $position, // Include the updated position
//                 'isroute' => $isRoute,
//                 'calculated_timing_in_minutes' => round($calculatedTimingInMinutes, 2) // Include the timing in minutes
//             ],
//             'technician' => [
//                 'user_id' => $technicianUser->id,
//                 'name' => $technicianUser->name,
//                 'latitude' => $technicianData->latitude,
//                 'longitude' => $technicianData->longitude,
//                 'full_address' => $technicianData->address_line1 . ', ' . 
//                                  $technicianData->city . ', ' . 
//                                  $technicianData->zipcode . ', ' . 
//                                  $technicianData->state_name,
//                 'position' => $position, // Include the updated position for technician
//                 'isroute' => $isRoute
//             ]
//         ];
//     }

    //     // Return success response with updated customer and technician data
//     return response()->json([
//         'success' => true,
//         'message' => 'Positions updated successfully',
//         'updated_customers' => $updatedCustomers
//     ]);
// }

    //  public function getLocationpositionsaveRoute(Request $request)
// {  
//     // Get the customers array from the request
//     $customers = $request->input('customers');
//     $updatedCustomers = [];
//     $previousLatitude = null; // Store previous latitude for distance calculation
//     $previousLongitude = null; // Store previous longitude for distance calculation
//     $speedKmPerHour = 50; // Assuming technician travels at 50 km/h

    //     // Parse the input date and set to start of day
//     $inputDate = Carbon::parse($request->input('date'))->format('Y-m-d');
//     $currentDate = Carbon::createFromFormat('Y-m-d', $inputDate)->startOfDay();

    //     // Fetch technician details (assuming technician ID is the same for all customers)
//     $technicianId = $customers[0]['technicianId'];
//     $technicianData = CustomerUserAddress::where('user_id', $technicianId)->first();
//     $technicianUser = User::find($technicianId);

    //     // Store technician's initial position
//     $technicianLatitude = $technicianData->latitude;
//     $technicianLongitude = $technicianData->longitude;

    //     // Loop through the customers and update each position and is_routes_map in the Schedule model
//     foreach ($customers as $index => $customer) {
//         $userId = $customer['user_id']; // This is the customer_id
//         $position = $customer['position'];
//         $jobId = $customer['jobid']; // Get jobid from the customer array
//         $isRoute = $customer['isroute']; // Capture isroute value

    //         // Fetch customer details from CustomerUserAddress and User model
//         $customerData = CustomerUserAddress::where('user_id', $userId)->first();
//         $customerUser = User::find($userId);

    //         // Initialize timing variables
//         $calculatedTimingInMinutes = 0; // To store the calculated time in minutes

    //         // Only calculate timing between points if isRoute is 1
//         if ($isRoute == 1) {
//             if ($index == 0) {
//                 // Calculate distance from technician to the first customer
//                 $distanceKm = $this->calculateDistance($technicianLatitude, $technicianLongitude, $customerData->latitude, $customerData->longitude);
//             } else {
//                 // Calculate distance from previous customer to the current customer
//                 $distanceKm = $this->calculateDistance($previousLatitude, $previousLongitude, $customerData->latitude, $customerData->longitude);
//             }

    //             // Calculate time in hours (Distance / Speed)
//             $calculatedTimeInHours = $distanceKm / $speedKmPerHour;

    //             // Convert time to minutes
//             $calculatedTimingInMinutes = $calculatedTimeInHours * 60; // Time in minutes
//         }

    //         // Update the position, is_routes_map, and job_onmap_reaching_timing in the Schedule model for the respective job
//         Schedule::where('job_id', $jobId) // Use job_id from the customer data
//             ->where('technician_id', $technicianId) // Ensure we're updating the correct technician's job
//             ->update([
//                 'position' => $position,
//                 'is_routes_map' => $isRoute, // Update is_routes_map based on isroute
//                 'job_onmap_reaching_timing' => round($calculatedTimingInMinutes, 2) // Store calculated timing between two positions
//             ]);

    //         // Update the previous position and coordinates for the next loop iteration
//         $previousLatitude = $customerData->latitude;
//         $previousLongitude = $customerData->longitude;

    //         // Collect customer and technician data for the response
//         $updatedCustomers[] = [
//             'customer' => [
//                 'user_id' => $customerUser->id,
//                 'name' => $customerUser->name,
//                 'latitude' => $customerData->latitude,
//                 'longitude' => $customerData->longitude,
//                 'full_address' => $customerData->address_line1 . ', ' . 
//                                  $customerData->city . ', ' . 
//                                  $customerData->zipcode . ', ' . 
//                                  $customerData->state_name,
//                 'position' => $position, // Include the updated position
//                 'isroute' => $isRoute,
//                 'calculated_timing_in_minutes' => round($calculatedTimingInMinutes, 2) // Include the timing in minutes
//             ],
//             'technician' => [
//                 'user_id' => $technicianUser->id,
//                 'name' => $technicianUser->name,
//                 'latitude' => $technicianData->latitude,
//                 'longitude' => $technicianData->longitude,
//                 'full_address' => $technicianData->address_line1 . ', ' . 
//                                  $technicianData->city . ', ' . 
//                                  $technicianData->zipcode . ', ' . 
//                                  $technicianData->state_name,
//                 'position' => $position, // Include the updated position for technician
//                 'isroute' => $isRoute
//             ]
//         ];
//     }

    //     // Return success response with updated customer and technician data
//     return response()->json([
//         'success' => true,
//         'message' => 'Positions updated successfully',
//         'updated_customers' => $updatedCustomers
//     ]);
// }

    // public function getLocationpositionsaveRoute(Request $request)
// {  
//     // Get the customers array from the request
//     $customers = $request->input('customers');
//     $updatedCustomers = [];
//     $previousLatitude = null; // Store previous latitude for distance calculation
//     $previousLongitude = null; // Store previous longitude for distance calculation
//     $speedKmPerHour = 50; // Assuming technician travels at 50 km/h

    //     // Parse the input date and set to start of day
//     $inputDate = Carbon::parse($request->input('date'))->format('Y-m-d');
//     $currentDate = Carbon::createFromFormat('Y-m-d', $inputDate)->startOfDay();

    //     // Fetch technician details (assuming technician ID is the same for all customers)
//     $technicianId = $customers[0]['technicianId'];
//     $technicianData = CustomerUserAddress::where('user_id', $technicianId)->first();
//     $technicianUser = User::find($technicianId);

    //     // Store technician's initial position
//     $technicianLatitude = $technicianData->latitude;
//     $technicianLongitude = $technicianData->longitude;

    //     // Loop through the customers and update each position and is_routes_map in the Schedule model
//     foreach ($customers as $index => $customer) {
//         $userId = $customer['user_id']; // This is the customer_id
//         $position = $customer['position'];
//         $jobId = $customer['jobid']; // Get jobid from the customer array
//         $isRoute = $customer['isroute']; // Capture isroute value

    //         // Fetch customer details from CustomerUserAddress and User model
//         $customerData = CustomerUserAddress::where('user_id', $userId)->first();
//         $customerUser = User::find($userId);

    //         // Initialize timing variables
//         $calculatedTimingInMinutes = 0; // To store the calculated time in minutes

    //         // Only calculate timing between points if isRoute is 1
//         if ($isRoute == 1) {
//             // Calculate timing for the first two positions specifically
//             if ($index == 0) {
//                 // Calculate distance from technician to the first customer
//                 $distanceKm = $this->calculateDistance($technicianLatitude, $technicianLongitude, $customerData->latitude, $customerData->longitude);
//             } elseif ($index == 1) {
//                 // Calculate distance from the first customer to the second customer
//                 $distanceKm = $this->calculateDistance($technicianLatitude, $technicianLongitude, $customerData->latitude, $customerData->longitude);
//             } else {
//                 // Use previous customer's coordinates for subsequent customers
//                 $distanceKm = $this->calculateDistance($previousLatitude, $previousLongitude, $customerData->latitude, $customerData->longitude);
//             }

    //             // Calculate time in hours (Distance / Speed)
//             $calculatedTimeInHours = $distanceKm / $speedKmPerHour;

    //             // Convert time to minutes
//             $calculatedTimingInMinutes = $calculatedTimeInHours * 60; // Time in minutes
//         }

    //         // Update the position, is_routes_map, and job_onmap_reaching_timing in the Schedule model for the respective job
//         Schedule::where('job_id', $jobId) // Use job_id from the customer data
//             ->where('technician_id', $technicianId) // Ensure we're updating the correct technician's job
//             ->update([
//                 'position' => $position,
//                 'is_routes_map' => $isRoute, // Update is_routes_map based on isroute
//                 'job_onmap_reaching_timing' => round($calculatedTimingInMinutes, 2) // Store calculated timing between two positions
//             ]);

    //         // Update the previous position and coordinates for the next loop iteration
//         $previousLatitude = $customerData->latitude;
//         $previousLongitude = $customerData->longitude;

    //         // Collect customer and technician data for the response
//         $updatedCustomers[] = [
//             'customer' => [
//                 'user_id' => $customerUser->id,
//                 'name' => $customerUser->name,
//                 'latitude' => $customerData->latitude,
//                 'longitude' => $customerData->longitude,
//                 'full_address' => $customerData->address_line1 . ', ' . 
//                                  $customerData->city . ', ' . 
//                                  $customerData->zipcode . ', ' . 
//                                  $customerData->state_name,
//                 'position' => $position, // Include the updated position
//                 'isroute' => $isRoute,
//                 'job_onmap_reaching_timing' => round($calculatedTimingInMinutes, 2) // Include the timing in minutes
//             ],
//             'technician' => [
//                 'user_id' => $technicianUser->id,
//                 'name' => $technicianUser->name,
//                 'latitude' => $technicianData->latitude,
//                 'longitude' => $technicianData->longitude,
//                 'full_address' => $technicianData->address_line1 . ', ' . 
//                                  $technicianData->city . ', ' . 
//                                  $technicianData->zipcode . ', ' . 
//                                  $technicianData->state_name,
//                 'position' => $position, // Include the updated position for technician
//                 'isroute' => $isRoute
//             ]
//         ];
//     }

    //     // Return success response with updated customer and technician data
//     return response()->json([
//         'success' => true,
//         'message' => 'Positions updated successfully',
//         'updated_customers' => $updatedCustomers
//     ]);
// }

    public function getLocationpositionsaveRoute(Request $request)
    {
        // Get the customers array from the request
        $customers = $request->input('customers');
        $updatedCustomers = [];
        $previousLatitude = null; // Store previous latitude for distance calculation
        $previousLongitude = null; // Store previous longitude for distance calculation
        $speedKmPerHour = 50; // Assuming technician travels at 50 km/h

        // Parse the input date and set to start of day
        $inputDate = Carbon::parse($request->input('date'))->format('Y-m-d');
        $currentDate = Carbon::createFromFormat('Y-m-d', $inputDate)->startOfDay();

        // Fetch technician details (assuming technician ID is the same for all customers)
        $technicianId = $customers[0]['technicianId'];
        $technicianData = CustomerUserAddress::where('user_id', $technicianId)->first();
        $technicianUser = User::find($technicianId);

        // Store technician's initial position
        $technicianLatitude = $technicianData->latitude;
        $technicianLongitude = $technicianData->longitude;

        // Initialize valid positions to track customers with isRoute = 1
        $validPositions = [];

        // Loop through the customers and update each position and is_routes_map in the Schedule model
        foreach ($customers as $index => $customer) {
            $userId = $customer['user_id']; // This is the customer_id
            $position = $customer['position'];
            $jobId = $customer['jobid']; // Get jobid from the customer array
            $isRoute = $customer['isroute']; // Capture isroute value

            // Fetch customer details from CustomerUserAddress and User model
            $customerData = CustomerUserAddress::where('user_id', $userId)->first();
            $customerUser = User::find($userId);

            // Initialize timing variable
            $calculatedTimingInMinutes = 0; // To store the calculated time in minutes

            // Only calculate timing if the current position is a route (isRoute = 1)
            if ($isRoute == 1) {
                // Check if valid positions exist to calculate timing
                if (count($validPositions) === 0) {
                    // If this is the first valid position, calculate distance from technician
                    $distanceKm = $this->calculateDistance($technicianLatitude, $technicianLongitude, $customerData->latitude, $customerData->longitude);
                } else {
                    // Use the last valid customer for timing calculation
                    $lastValidCustomer = end($validPositions);
                    $distanceKm = $this->calculateDistance($lastValidCustomer->latitude, $lastValidCustomer->longitude, $customerData->latitude, $customerData->longitude);
                }

                // Calculate time in hours (Distance / Speed)
                $calculatedTimeInHours = $distanceKm / $speedKmPerHour;

                // Convert time to minutes
                $calculatedTimingInMinutes = $calculatedTimeInHours * 60; // Time in minutes

                // Add the current customer to the valid positions
                $validPositions[] = $customerData;
            }

            // Update the position, is_routes_map, and job_onmap_reaching_timing in the Schedule model for the respective job
            Schedule::where('job_id', $jobId) // Use job_id from the customer data
                ->where('technician_id', $technicianId) // Ensure we're updating the correct technician's job
                ->update([
                    'position' => $position,
                    'is_routes_map' => $isRoute, // Update is_routes_map based on isroute
                    'job_onmap_reaching_timing' => $isRoute == 1 ? round($calculatedTimingInMinutes, 2) : null // Store calculated timing only if isRoute is 1
                ]);

            // Collect customer and technician data for the response
            $updatedCustomers[] = [
                'customer' => [
                    'user_id' => $customerUser->id,
                    'name' => $customerUser->name,
                    'latitude' => $customerData->latitude,
                    'longitude' => $customerData->longitude,
                    'full_address' => $customerData->address_line1 . ', ' .
                        $customerData->city . ', ' .
                        $customerData->zipcode . ', ' .
                        $customerData->state_name,
                    'position' => $position, // Include the updated position
                    'isroute' => $isRoute,
                    'job_onmap_reaching_timing' => $isRoute == 1 ? round($calculatedTimingInMinutes, 2) : null // Include timing only if isRoute is 1
                ],
                'technician' => [
                    'user_id' => $technicianUser->id,
                    'name' => $technicianUser->name,
                    'latitude' => $technicianData->latitude,
                    'longitude' => $technicianData->longitude,
                    'full_address' => $technicianData->address_line1 . ', ' .
                        $technicianData->city . ', ' .
                        $technicianData->zipcode . ', ' .
                        $technicianData->state_name,
                    'position' => $position, // Include the updated position for technician
                    'isroute' => $isRoute
                ]
            ];
        }

        // Return success response with updated customer and technician data
        return response()->json([
            'success' => true,
            'message' => 'Positions updated successfully',
            'updated_customers' => $updatedCustomers
        ]);
    }

    public function getALlRoutingJob(Request $request)
    {
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $currentDate = Carbon::now($timezone_name);
        $filterDate = Carbon::parse($request->date)->format('Y-m-d');
        $routing = $request->input('routing');
        $dateDay = $request->input('dateDay');
        $chooseFrom = $request->input('chooseFrom');
        $chooseTo = $request->input('chooseTo');
        $startDate = $currentDate->copy()->startOfDay();
        $endDate = $currentDate->copy()->endOfDay();

        // Handle date ranges based on `dateDay`
        switch ($dateDay) {
            case 'today':
                $endDate = $startDate->copy()->endOfDay();
                break;
            case 'tomorrow':
                $startDate = $currentDate->copy()->addDay()->startOfDay();
                $endDate = $currentDate->copy()->addDay()->endOfDay();
                break;
            case 'nextdays':
                $endDate = $currentDate->copy()->addDays(2)->endOfDay();
                break;
            case 'week':
                $startDate = $currentDate->copy()->startOfDay();
                $endDate = $currentDate->copy()->addDays(6)->endOfDay();
                break;
            case 'chooseDate':
                if ($chooseFrom && $chooseTo) {
                    $startDate = Carbon::parse($chooseFrom, $timezone_name)->startOfDay();
                    $endDate = Carbon::parse($chooseTo, $timezone_name)->endOfDay();
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid date range.'], 400);
                }
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid dateDay value.'], 400);
        }

        // Fetch routing jobs
        $routeJobs = RoutingJOb::where('user_id', $request->tech_id)
            ->whereBetween('schedule_date_time', [$startDate, $endDate])
            ->get();

        $schedule = collect();

        $routeJobs->each(function ($routeJob) use ($routing, &$schedule, $startDate, $endDate) {
            $jobIds = [];

            // Determine which route to use based on `$routing`
            switch ($routing) {
                case 'bestroute':
                    $jobIds = json_decode($routeJob->best_route, true);
                    break;
                case 'shortestroute':
                    $jobIds = json_decode($routeJob->short_route, true);
                    break;
                case 'customizedroute':
                    $jobIds = explode(',', $routeJob->custom_route);
                    break;
                default:
                    return; // Skip if routing is invalid
            }

            // Fetch the schedule for the extracted job IDs
            $currentSchedule = Schedule::with([
                'JobModel' => function ($query) {
                    $query->with([
                        'user',
                        'jobproductinfohasmany.product',
                        'jobserviceinfohasmany.service',
                        'addresscustomer',
                        'JobAppliances.Appliances.manufacturer',
                        'JobAppliances.Appliances.appliance',
                    ]);
                },
                'technician',
            ])
            ->whereIn('job_id', $jobIds)
            ->whereBetween('start_date_time', [$startDate, $endDate])
            ->where('show_on_schedule', 'yes')
            ->get();

            $schedule = $schedule->merge($currentSchedule);
        });

        // Remove duplicates if necessary
        $schedule = $schedule->unique('id');

        // Return the response
        return response()->json($schedule);
    }

    public function checkroutingtech(Request $request)
    {
        $technicianId = $request->input('technician_id');
        $date = $request->input('date');

        // Adjust date format if necessary
        $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');

        // Query matching data
        $jobs = RoutingJOb::where('user_id', $technicianId)
            ->whereDate('schedule_date_time', $formattedDate) 
            ->get();
        $user = User::where('id', $technicianId)->first();

        // Return the response
        return response()->json([
            'status' => 'success',
            'data' => $jobs,
            'user' => $user,
        ]);
    }

}
