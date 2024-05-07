<?php

namespace App\Http\Controllers;

use App\Models\Appliances;
use App\Models\AppliancesType;
use App\Models\BusinessHours;
use App\Models\CustomerUserAddress;
use App\Models\Event;
use App\Models\JobActivity;
use App\Models\JobModel;
use App\Models\LocationCity;
use App\Models\LocationServiceArea;
use App\Models\LocationState;
use App\Models\Manufacturer;
use App\Models\User;
use App\Models\Service;
use App\Models\Products;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\ServiceCategory;
use App\Models\SiteJobFields;
use App\Models\SiteLeadSource;
use App\Models\SiteTags;
use App\Models\UserAppliances;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Storage;

class ScheduleController extends Controller
{


    public function index(Request $request)
    {
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
            $currentDate = Carbon::now();
        }

        $currentDay = $currentDate->format('l'); 
        $currentDayLower = strtolower($currentDay); 
        // Query the business hours for the given day
        $hours = BusinessHours::where('day', $currentDayLower)->first();

        $formattedDate = $currentDate->format('l, F j, Y');

        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');

        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $filterDate = $currentDate->format('Y-m-d');

        $TodayDate = Carbon::now()->format('Y-m-d');

        $user_array = [];

        $user_data_array = [];

        $assignment_arr = [];

        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $tech = User::where('role', 'technician')->get();

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
                        $datetimeString = $value2->start_date_time;
                        $time = date("h:i A", strtotime($datetimeString));
                        $assignment_arr[$value][$time][] = $value2;
                    }
                }

                $schedule_arr[$value] = [];

                $schedule = Schedule::with('JobModel', 'technician')->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")->get();
                if (isset($schedule) && !empty($schedule->count())) {
                    foreach ($schedule as $k => $item) {
                        $datetimeString = $item->start_date_time;
                        $time = date("h:i A", strtotime($datetimeString));
                        $schedule_arr[$value][$time][] = $item;
                        // dd($schedule_arr);
                    }
                }
            }
        }


        return view('schedule.index', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr','hours'));
    }

    public function schedule_new(Request $request)
    {
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
            $currentDate = Carbon::now();
        }

        $formattedDate = $currentDate->format('l, F j, Y');

        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');

        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $filterDate = $currentDate->format('Y-m-d');

        $TodayDate = Carbon::now()->format('Y-m-d');

        $user_array = [];

        $user_data_array = [];

        $assignment_arr = [];

        $schedule_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $tech = User::where('role', 'technician')->get();

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
                        $datetimeString = $value2->start_date_time;
                        $time = date("h:i A", strtotime($datetimeString));
                        $assignment_arr[$value][$time][] = $value2;
                    }
                }

                $schedule_arr[$value] = [];

                $schedule = Schedule::with('JobModel', 'technician')->where('technician_id', $value)
                    ->where('start_date_time', 'LIKE', "%$filterDate%")->get();
                if (isset($schedule) && !empty($schedule->count())) {
                    foreach ($schedule as $k => $item) {
                        $datetimeString = $item->start_date_time;
                        $time = date("h:i A", strtotime($datetimeString));
                        $schedule_arr[$value][$time][] = $item;
                        // dd($schedule_arr);
                    }
                }
            }
        }


        return view('schedule.schedule_new', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate', 'users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags', 'cities', 'cities1', 'TodayDate', 'tech', 'schedule_arr'));
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

            $tags = SiteTags::all();

            $dateTime = $dateTime->format('Y-m-d H:i:s');

            $technician = User::join('user_address', 'user_address.user_id', 'users.id')->where('id', $id)->first();

            $getServices = Service::all();
            $serviceCat =ServiceCategory::with('Services')->get();

            $getProduct = Products::whereNotNull('base_price')->where('status', 'Publish')->get();
            $locationStates = LocationState::all();

            $leadSources = SiteLeadSource::all();

            $tags = SiteJobFields::all(); // Fetch all tags

            return view('schedule.create_job', compact('tags', 'leadSources', 'locationStates', 'technician', 'dateTime', 'manufacturers', 'appliances', 'getServices', 'getProduct', 'tags','hours','time','serviceCat'));
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
                        ->select('user_address.address_line1','user_address.address_line2','user_address.city', 'location_states.state_name',  'location_states.state_code', 'user_address.zipcode')
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
                       $customers .= $value->mobile . ' / ' ;
                    }
                    if (isset($value->email) && !empty($value->email)) {
                       $customers .= $value->email   ;
                    }
                    if (isset($value->email) && !empty($value->email) && isset($value->email) && !empty($value->email)) {
                    $customers .= '<br />'   ;
                    }
                    if (isset($getCustomerAddress->address_line1) && !empty($getCustomerAddress->address_line1)){
                     $customers .=  $getCustomerAddress->address_line1 . ', ';
                    }
                    if (isset($getCustomerAddress->address_line2) && !empty($getCustomerAddress->address_line2)){
                     $customers .= $getCustomerAddress->address_line2 . ', ';
                    }
                    if (isset($getCustomerAddress->address_line2) && !empty($getCustomerAddress->address_line2)){
                     $customers .= $getCustomerAddress->address_line2 . ', ';
                    }
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city)){
                     $customers .= $getCustomerAddress->city . ', ';
                    }
                    if (isset($getCustomerAddress->state_name) && !empty($getCustomerAddress->state_name)){
                     $customers .= $getCustomerAddress->state_name . ', ';
                    }
                    if (isset($getCustomerAddress->zipcode) && !empty($getCustomerAddress->zipcode)){
                     $customers .= $getCustomerAddress->zipcode ;
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

        //try {

        if (isset($data) && !empty($data)) {

            if (isset($data['job_id']) && !empty($data['job_id'])) {

                $start_date_time = Carbon::parse($data['datetime']);

                $duration = (int) $data['duration'];

                $end_date_time = $start_date_time->copy()->addMinutes($duration);

                $technician = User::where('id', $data['technician_id'])->first();

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $getCustomerDetails = User::with('userAddress')
                    ->where('id', $data['customer_id'])
                    ->first();
                // dd($getCustomerDetails);

                do {
                    $randomSixDigit = mt_rand(100000, 999999);
                    $exists = DB::table('jobs')->where('job_code', $randomSixDigit)->exists();
                } while ($exists); 

                if (is_array($request->tags)){  
                    $tagIds = implode(',', $request->tags);
                }else {
                    $tagIds = '';
                }
                
                $customer_name = User::where('id',$data['customer_id'])->first();
                $technician_name = User::where('id',$data['technician_id'])->first();

                $jobsData = [
                    'job_code' => (isset($randomSixDigit) && !empty($randomSixDigit)) ? $randomSixDigit : '',
                    'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'appliances_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : $data['exist_appl_id'],
                    'description' => (isset($data['job_description']) && !empty($data['job_description'])) ? $data['job_description'] : '',
                    'priority' => (isset($data['priority']) && !empty($data['priority'])) ? $data['priority'] : '',
                    'service_area_id' => 1,
                    'warranty_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
                    'tax' => (isset($data['tax_total']) && !empty($data['tax_total'])) ? $data['tax_total'] : '',
                    'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                    'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                    'subtotal' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                    'is_confirmed' => (isset($data['is_confirmed']) && !empty($data['is_confirmed'])) ? $data['is_confirmed'] : 'no',
                    'tag_ids' => $tagIds,
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
                    'start_date_time' => $start_date_time,
                    'end_date_time' => $end_date_time,
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'start_slot' => $start_date_time->format('H'),
                    'end_slot' => $end_date_time->format('H'),
                    'technician_note_id' => $jobNotesID
                ];

                $jobAssignedID = DB::table('job_assigned')->where('job_id', $data['job_id'])->update($JobAssignedData);

                if(isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])){
              
                    $userappl = [
                        'appliance_id' =>(isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])) ? $data['exist_appl_id'] : '',
                    ];
                    $addAppliancesUser = DB::table('job_appliance')->where('job_id', $data['job_id'])->update($userappl);

                }else{
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
                        'appliance_id' => $userappliances ,
                    ];
                    $addAppliancesUser = DB::table('job_appliance')->insertGetId($userappl);
                }

                if (isset($data['service_id']) && !empty($data['service_id'])) {

                    $serviceData = [
                        'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                }
                if (isset($data['new_service']) && !empty($data['new_service'])) {
                    $serviceData = [
                        'service_id' => (isset($data['new_service']) && !empty($data['new_service'])) ? $data['new_service'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                if (isset($data['product_id']) && !empty($data['product_id'])) {

                    $productData = [
                        'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                    ];

                    $productDataInsert = DB::table('job_product_items')->where('job_id', $data['job_id'])->update($productData);
                }

                if (isset($data['new_product']) && !empty($data['new_product'])) {
                    $productData = [
                        'product_id' => (isset($data['new_product']) && !empty($data['new_product'])) ? $data['new_product'] : '',
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

                    $schedule->save();

                    $scheduleId = $schedule->id;
                }
                $now = Carbon::now();
                $formattedDate = $start_date_time->format('D, M j');
                $formattedTime = $now->format('g:ia');
                $formattedDateTime = "{$formattedDate} at {$formattedTime}";
                $activity ='Job Re-Scheduled for '. $formattedDateTime;
               app('JobActivityManager')->addJobActivity($data['job_id'], $activity);
               app('sendNotices')(
                    "Reschedule Job","Reschedule Job (#{$jobId} - {$customer_name->name}) added by {$technician_name->name}",
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

                $start_date_time = Carbon::parse($data['datetime']);

                $duration = (int) $data['duration'];

                $end_date_time = $start_date_time->copy()->addMinutes($duration);

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $getCustomerDetails = User::select('user_address.*')
                    ->join('user_address', 'user_address.user_id', 'users.id')
                    ->where('users.id', $data['customer_id'])->where('user_address.address_type', $data['customer_address'])
                    ->first();

                if (is_array($request->tags)){  
                  $tagIds = implode(',', $request->tags);
                }else {
                    $tagIds = '';
                }

                do {
                    $randomSixDigit = mt_rand(100000, 999999);
                    $exists = DB::table('jobs')->where('job_code', $randomSixDigit)->exists();
                } while ($exists); 
                $customer_name = User::where('id',$data['customer_id'])->first();
                $technician_name = User::where('id',$data['technician_id'])->first();

                if($getCustomerDetails->state_name == 'NY'){
                     $tax_details = '4% for NY';
                }elseif($getCustomerDetails->state_name == 'TX'){
                     $tax_details = '6.25% for TX';
                }
    

                $jobsData = [
                    'job_code' => (isset($randomSixDigit) && !empty($randomSixDigit)) ? $randomSixDigit : '',
                    'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'appliances_id' => (isset($data['appliances']) && !empty($data['appliances'])) ? $data['appliances'] : $data['exist_appl_id'],
                    'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                    'warranty_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
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
                    'state' => (isset($getCustomerDetails->state_name) && !empty($getCustomerDetails->state_name)) ? $getCustomerDetails->state_name : '',
                    'tax_details' =>  (isset($tax_details) && !empty($tax_details)) ? $tax_details : '',
                    'zipcode' => (isset($getCustomerDetails->zipcode) && !empty($getCustomerDetails->zipcode)) ? $getCustomerDetails->zipcode : '',
                    'latitude' => (isset($getCustomerDetails->latitude) && !empty($getCustomerDetails->latitude)) ? $getCustomerDetails->latitude : 0,
                    'longitude' => (isset($getCustomerDetails->longitude) && !empty($getCustomerDetails->longitude)) ? $getCustomerDetails->longitude : 0,
                    'is_confirmed' => (isset($data['is_confirmed']) && !empty($data['is_confirmed'])) ? $data['is_confirmed'] : 'no',
                    'added_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'job_field_ids' => 0,
                    'tag_ids' => $tagIds,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobId = DB::table('jobs')->insertGetId($jobsData);

                

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

            if(isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])){
              
                 $userappl = [
                     'job_id' => $jobId,
                     'appliance_id' =>(isset($data['exist_appl_id']) && !empty($data['exist_appl_id'])) ? $data['exist_appl_id'] : '',
                ];
                $addAppliancesUser = DB::table('job_appliance')->insertGetId($userappl);

            }else{
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
                     'appliance_id' => $userappliances ,
                ];
                $addAppliancesUser = DB::table('job_appliance')->insertGetId($userappl);
            }

                if (isset($data['services']) && !empty($data['services'])) {

                    $serviceData = [
                        'service_id' => (isset($data['services']) && !empty($data['services'])) ? $data['services'] : '',
                        'job_id' => $jobId,
                        'base_price' => (isset($data['service_cost']) && !empty($data['service_cost'])) ? $data['service_cost'] : 0,
                        'tax' => $service_tax,
                        'discount' => (isset($data['service_discount_amount']) && !empty($data['service_discount_amount'])) ? $data['service_discount_amount'] : 0,
                        'sub_total' => (isset($data['service_total']) && !empty($data['service_total'])) ? $data['service_total'] : 0,
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->insertGetId($serviceData);
                }

                if (isset($data['new_service']) && !empty($data['new_service'])) {

                    $serviceData = [
                        'service_id' => (isset($data['new_service']) && !empty($data['new_service'])) ? $data['new_service'] : '',
                        'job_id' => $jobId,
                        'base_price' => (isset($data['new_service_cost']) && !empty($data['new_service_cost'])) ? $data['new_service_cost'] : 0,
                        'tax' => $service_tax,
                        'discount' => (isset($data['new_service_discount_amount']) && !empty($data['new_service_discount_amount'])) ? $data['new_service_discount_amount'] : 0,
                        'sub_total' => (isset($data['new_service_total']) && !empty($data['new_service_total'])) ? $data['new_service_total'] : 0,
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->insertGetId($serviceData);
                }

                

                if (isset($data['products']) && !empty($data['products'])) {

                    $productData = [
                        'product_id' => (isset($data['products']) && !empty($data['products'])) ? $data['products'] : '',
                        'job_id' => $jobId,
                        'base_price' => (isset($data['product_cost']) && !empty($data['product_cost'])) ? $data['product_cost'] : 0,
                        'tax' => $product_tax,
                        'discount' => (isset($data['product_discount_amount']) && !empty($data['product_discount_amount'])) ? $data['product_discount_amount'] : 0,
                        'sub_total' => (isset($data['product_total']) && !empty($data['product_total'])) ? $data['product_total'] : 0,
                    ];

                    $productDataInsert = DB::table('job_product_items')->insertGetId($productData);
                }

                if (isset($data['new_product']) && !empty($data['new_product'])) {


                    $newproductData = [
                        'product_id' => (isset($data['new_product']) && !empty($data['new_product'])) ? $data['new_product'] : '',
                        'job_id' => $jobId,
                        'base_price' => (isset($data['new_product_cost']) && !empty($data['new_product_cost'])) ? $data['new_product_cost'] : 0,
                        'tax' => $service_tax,
                        'discount' => (isset($data['new_product_discount_amount']) && !empty($data['new_product_discount_amount'])) ? $data['new_product_discount_amount'] : 0,
                        'sub_total' => (isset($data['new_product_total']) && !empty($data['new_product_total'])) ? $data['new_product_total'] : 0,
                    ];
                    $newproductDataInsert = DB::table('job_product_items')->insertGetId($newproductData);
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

                    $schedule->save();
                    $scheduleId = $schedule->id;
                }
                $now = Carbon::now();
                $formattedDate = $start_date_time->format('D, M j');
                $formattedTime = $now->format('g:ia');
                $formattedDateTime = "{$formattedDate} at {$formattedTime}";

                $activity ='Job scheduled for '. $formattedDateTime;
               app('JobActivityManager')->addJobActivity($jobId, $activity);
               app('sendNotices')(
                     "New Job","New Job (#{$jobId} - {$customer_name->name}) added by {$technician_name->name}",
                    url()->current(), 
                    'job'
                );
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

        // } catch (\Exception $e) {

        //     Storage::append('CreateSchedule.log', ' error_msg -- ' . json_encode($e->getMessage()) . ' line number: ' . json_encode($e->getLine()) . ' File: ' . json_encode($e->getFile()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

        //     return 'false';
        // }

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

            $filterCustomer = User::where('mobile',  $phone)
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
                // dd($getCustomerDetails);

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

        if($request->event_type == 'full'){

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
        
        }else{

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

        $tags = SiteTags::all(); // Fetch all tags

        return view('schedule.new_customer', compact('locationStates', 'leadSources', 'tags'));
    }

    public function userstate(Request $request)
    {

        $a = CustomerUserAddress::where('user_id', $request->technicianId)->with('locationStateName')->first();
        $address = $a->locationStateName;

        $job = User::where('name', $request->technician_name)->first();

        $service_area_ids = explode(',', $job->service_areas);
    
        $area_locations = LocationServiceArea::whereIn('area_id', $service_area_ids)->get();

        $results = [];

        $area_locations->each(function($location) use (&$results) {
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
        // dd();
        $tech_add = CustomerUserAddress::where('user_id' , $request->tech_id)->first();
        $address = $tech_add->latitude .','. $tech_add->longitude;
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
       $appliance = UserAppliances::with('appliance','manufacturer')->where('user_id',$request->id)->get();

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




}
    