<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\LocationCity;
use App\Models\UsersDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\UserFiles;

use App\Models\Payment;


use App\Models\JobModel;
use App\Models\Leadsource;


use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use App\Models\UserTag;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\SiteTags;
use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\SiteLeadSource;
use App\Models\CustomerUserMeta;
use App\Models\UserNotesCustomer;
use App\Models\UserTagIdCategory;

use App\Models\CustomerUserAddress;
use Illuminate\Support\Facades\File;
use App\Models\UserLeadSourceCustomer;
use App\Models\UsersActivity;
use App\Models\UsersSettings;
use Illuminate\Support\Facades\Validator;


use Exception;



class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    // public function index($status = null)
    // {
    //     $user_auth = auth()->user();
    //     $user_id = $user_auth->id;
    //     $permissions_type = $user_auth->permissions_type;
    //     $module_id = 2;
    //     $locationStates = LocationState::all();

    //     $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
    //     if ($permissionCheck === true) {
    //         // Proceed with the action
    //     } else {
    //         return $permissionCheck; // This will handle the redirection
    //     }

    //     $usersQuery = User::where('role', 'customer');

    //     if ($status == "deactive") {
    //         $usersQuery->where('status', 'deactive');
    //     } else {
    //         $usersQuery->where('status', 'active');
    //     }

    //     $users = $usersQuery->orderBy('created_at', 'desc')->paginate(50);

    // return view('users.index', compact('users', 'locationStates'));
    // }

public function index(Request $request, $status = null)
{
    // Get the query parameters
    $workupdate = $request->query('workupdate');
    $stateIds = $request->query('state', []); // Default to an empty array if no values are present
  $jobs = $request->query('jobs');
    // Filter out empty values
    $stateIds = array_filter($stateIds, function($value) {
        return !is_null($value) && $value !== '';
    });

    //dd($stateIds); // Debug to check the received values

    // Get the authenticated user and their details
    $user_auth = auth()->user();
    $user_id = $user_auth->id;
    $permissions_type = $user_auth->permissions_type;
    $module_id = 2;

    // Fetch location states for the view
    $locationStates = LocationState::all();

    // Check user permissions
    $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
    if ($permissionCheck !== true) {
        return $permissionCheck; // Handle the redirection if permission check fails
    }

    // Start the query for users with role 'customer'
    $usersQuery = User::where('role', 'customer');

    // Apply status filter if provided
    if ($status === "deactive") {
        $usersQuery->where('status', 'deactive');
    } elseif ($status === "active") {
        $usersQuery->where('status', 'active');
    }

    // Apply work update filter if provided
    if ($workupdate === 'yes') {
        $usersQuery->where('is_updated', 'yes');
    } elseif ($workupdate === 'no') {
        $usersQuery->where('is_updated', 'no');
    }

    // Check if state IDs are present
    if (!empty($stateIds)) {
        // Apply state filter
        $usersQuery->whereIn('user_address.state_id', $stateIds);

        // Apply joins if state IDs are present
        $usersQuery->leftJoin('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->select('users.*', 'location_states.state_name', 'location_cities.city')
            ->orderBy('users.created_at', 'desc');
    } else {
        // Fetch users without joins
        $usersQuery->orderBy('created_at', 'desc');
    }
  $timezone_name = session('timezone_name');
    $now = Carbon::now($timezone_name);

    // Handle job filters
    if ($jobs === 'upcoming') {
        // Upcoming jobs filter
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->where('job_assigned.start_date_time', '>', $now)
            ->select('users.*', 'job_assigned.start_date_time');
    } elseif ($jobs === 'this_month') {
        // Jobs this month
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->whereMonth('job_assigned.start_date_time', $now->month)
            ->whereYear('job_assigned.start_date_time', $now->year)
            ->select('users.*', 'job_assigned.start_date_time');
    } elseif ($jobs === 'last_month') {
        // Jobs last month
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->whereMonth('job_assigned.start_date_time', $now->subMonth()->month)
            ->whereYear('job_assigned.start_date_time', $now->year)
            ->select('users.*', 'job_assigned.start_date_time');
    } else {
        // Show all users
        $usersQuery->select('users.*');
    }
    // Paginate the results
    $users = $usersQuery->paginate(50);

    // Pass the data to the view
    return view('users.index', compact('users', 'locationStates', 'workupdate', 'stateIds', 'jobs'));
}


public function customers_demo_iframe(Request $request, $status = null)
{
    // Get the query parameters
    $workupdate = $request->query('workupdate');
    $stateIds = $request->query('state', []); // Default to an empty array if no values are present
  $jobs = $request->query('jobs');
    // Filter out empty values
    $stateIds = array_filter($stateIds, function($value) {
        return !is_null($value) && $value !== '';
    });

    //dd($stateIds); // Debug to check the received values

    // Get the authenticated user and their details
    $user_auth = auth()->user();
    $user_id = $user_auth->id;
    $permissions_type = $user_auth->permissions_type;
    $module_id = 2;

    // Fetch location states for the view
    $locationStates = LocationState::all();

    // Check user permissions
    $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
    if ($permissionCheck !== true) {
        return $permissionCheck; // Handle the redirection if permission check fails
    }

    // Start the query for users with role 'customer'
    $usersQuery = User::where('role', 'customer');

    // Apply status filter if provided
    if ($status === "deactive") {
        $usersQuery->where('status', 'deactive');
    } elseif ($status === "active") {
        $usersQuery->where('status', 'active');
    }

    // Apply work update filter if provided
    if ($workupdate === 'yes') {
        $usersQuery->where('is_updated', 'yes');
    } elseif ($workupdate === 'no') {
        $usersQuery->where('is_updated', 'no');
    }

    // Check if state IDs are present
    if (!empty($stateIds)) {
        // Apply state filter
        $usersQuery->whereIn('user_address.state_id', $stateIds);

        // Apply joins if state IDs are present
        $usersQuery->leftJoin('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->select('users.*', 'location_states.state_name', 'location_cities.city')
            ->orderBy('users.created_at', 'desc');
    } else {
        // Fetch users without joins
        $usersQuery->orderBy('created_at', 'desc');
    }
  $timezone_name = session('timezone_name');
    $now = Carbon::now($timezone_name);

    // Handle job filters
    if ($jobs === 'upcoming') {
        // Upcoming jobs filter
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->where('job_assigned.start_date_time', '>', $now)
            ->select('users.*', 'job_assigned.start_date_time');
    } elseif ($jobs === 'this_month') {
        // Jobs this month
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->whereMonth('job_assigned.start_date_time', $now->month)
            ->whereYear('job_assigned.start_date_time', $now->year)
            ->select('users.*', 'job_assigned.start_date_time');
    } elseif ($jobs === 'last_month') {
        // Jobs last month
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->whereMonth('job_assigned.start_date_time', $now->subMonth()->month)
            ->whereYear('job_assigned.start_date_time', $now->year)
            ->select('users.*', 'job_assigned.start_date_time');
    } else {
        // Show all users
        $usersQuery->select('users.*');
    }
    // Paginate the results
    $users = $usersQuery->paginate(50);

    // Pass the data to the view
    return view('users.index_customers_demo_iframe', compact('users', 'locationStates', 'workupdate', 'stateIds', 'jobs'));
}





    // public function search(Request $request)
    // {
    //     $query = $request->input('search');
    //     $locationStates = LocationState::all();

    //     $users = User::where('role', 'customer')
    //         ->where(function ($queryBuilder) use ($query) {
    //             $queryBuilder->where('name', 'like', '%' . $query . '%')
    //                 ->orWhere('email', 'like', '%' . $query . '%');
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(50);

    //     $tbodyHtml = view('users.search_content', compact('users','locationStates'))->render();

    //     return response()->json(['tbody' => $tbodyHtml]);
    // }
public function search(Request $request)
{
    // Get the query parameters
    $query = $request->input('search');
    $workupdate = $request->query('workupdate');
    $stateIds = $request->query('state', []); // Default to an empty array if no values are present
    $jobs = $request->query('jobs');

    // Filter out empty values
    $stateIds = array_filter($stateIds, function($value) {
        return !is_null($value) && $value !== '';
    });

    // Fetch location states for the view
    $locationStates = LocationState::all();

    // Start the query for users with role 'customer'
    $usersQuery = User::where('role', 'customer');

    // Apply search filter if provided
   

    // Apply work update filter if provided
    if ($workupdate === 'yes') {
        $usersQuery->where('is_updated', 'yes');
    } elseif ($workupdate === 'no') {
        $usersQuery->where('is_updated', 'no');
    }

    // Apply state filter if state IDs are present
    if (!empty($stateIds)) {
        $usersQuery->leftJoin('user_address', 'users.id', '=', 'user_address.user_id')
                   ->whereIn('user_address.state_id', $stateIds)
                   ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
                   ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
                   ->select('users.*', 'location_states.state_name', 'location_cities.city');
    }

    // Apply jobs filter if 'jobs' parameter is present
     $timezone_name = session('timezone_name');
    $now = Carbon::now($timezone_name);

    // Handle job filters
    if ($jobs === 'upcoming') {
        // Upcoming jobs filter
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->where('job_assigned.start_date_time', '>', $now)
            ->select('users.*', 'job_assigned.start_date_time');
    } elseif ($jobs === 'this_month') {
        // Jobs this month
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->whereMonth('job_assigned.start_date_time', $now->month)
            ->whereYear('job_assigned.start_date_time', $now->year)
            ->select('users.*', 'job_assigned.start_date_time');
    } elseif ($jobs === 'last_month') {
        // Jobs last month
        $usersQuery->leftJoin('job_assigned', 'users.id', '=', 'job_assigned.customer_id')
            ->whereMonth('job_assigned.start_date_time', $now->subMonth()->month)
            ->whereYear('job_assigned.start_date_time', $now->year)
            ->select('users.*', 'job_assigned.start_date_time');
    } else {
        // Show all users
        $usersQuery->select('users.*');
    }

    
 $usersQuery->where(function ($queryBuilder) use ($query) {
        $queryBuilder->where('name', 'like', '%' . $query . '%')
                     ->orWhere('email', 'like', '%' . $query . '%');
    });
    // Order and paginate the results
    $users = $usersQuery->orderBy('users.created_at', 'desc')->paginate(50);

    // Render the view and return JSON response
    $tbodyHtml = view('users.search_content', compact('users', 'locationStates','workupdate', 'stateIds', 'jobs'))->render();
    return response()->json(['tbody' => $tbodyHtml]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
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


        return view('users.create', compact('users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags',));
    }

     public function customers_demo_iframe_create()
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


        return view('users.customers_demo_iframe_create', compact('users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags',));
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            //'email' => 'required|email|unique:users,email',
            'mobile_phone' => 'required|max:20',
            'address1' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip_code' => 'max:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->name = $request['display_name'];
        $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        // $user->company = $request['company'];
        // $user->user_type = $request['user_type'];
        //  $user->position = $request['role'];
        $user->source_id = $request['source_id'];
        $user->customer_id = 0;
        $user->password = Hash::make($request['password']);
        $user->is_employee = 'yes';

        $user->save();
        $userId = $user->id;
        app('sendNotices')('New Customer', 'New Customer Added at ' . now(), url()->current(), 'customer');


        if ($request->hasFile('image')) {
            $directoryName = $userId;
            $directoryPath = public_path('images/Uploads/users/' . $directoryName);

            // Ensure the directory exists; if not, create it
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->move($directoryPath, $imageName);

            $user->user_image = $imageName;
            $user->save();
        }

        $userId = $user->id;
        // $currentTimestamp = now();

        // $user->meta()->createMany([
        //     ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $request['first_name']],
        //     ['user_id' => $userId, 'meta_key' => 'last_name', 'meta_value' => $request['last_name']],
        //     ['user_id' => $userId, 'meta_key' => 'home_phone', 'meta_value' => $request['home_phone']],
        //     ['user_id' => $userId, 'meta_key' => 'work_phone', 'meta_value' => $request['work_phone']],
        //     ['user_id' => $userId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
        //     ['user_id' => $userId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        // ]);

        $usersDetails = new UsersDetails();
        $usersDetails->user_id = $userId;
        $usersDetails->unique_number = 0;
        $usersDetails->lifetime_value = 0;
        $usersDetails->license_number = 0;
        // $usersDetails->dob = 0;
        $usersDetails->ssn = 0;
        $usersDetails->update_done = 'no';


        $usersDetails->first_name = $request->input('first_name');
        $usersDetails->last_name = $request->input('last_name');
        $usersDetails->home_phone = $request->input('home_phone');
        $usersDetails->work_phone = $request->input('work_phone');
        $usersDetails->customer_position = $request->input('role');

        $usersDetails->additional_email = $request->input('additional_email');

        $usersDetails->customer_company = $request->input('company');

        $usersDetails->customer_type = $request->input('user_type');

        $usersDetails->save();

        $customerAddress = new CustomerUserAddress();
        $customerAddress->user_id = $userId;
        $customerAddress->address_line1 = $request['address1'];
        $customerAddress->address_line2 = $request['address_unit'];
        $customerAddress->address_primary = ($request['address_type'] == 'home') ? 'yes' : 'no';
        $customerAddress->city = $request['city'];
        // $customerAddress->city_id = $request['city_id'];
        $customerAddress->address_type = $request['address_type'];
        $customerAddress->state_id = $request['state_id'];
        $nearestZip = DB::table('location_cities')
            ->select('zip')
            ->where('zip', 'like', '%' . $request['zip_code'] . '%')

            ->orderByRaw('ABS(zip - ' . $request['zip_code'] . ')')
            ->first();

        // If a nearest ZIP code is found, use it; otherwise, use the provided ZIP code
        $customerAddress->zipcode = $nearestZip ? $nearestZip->zip : $request['zip_code'];

        $city = DB::table('location_cities')->where('city', $request['city'])->first();

        if ($city) {
            $matchingCity = DB::table('location_cities')->where('zip', $request['zip_code'])->first();

            if ($matchingCity) {
                $customerAddress->city_id = $matchingCity->city_id;
            } else {
                $nearestCity = DB::table('location_cities')
                    ->select('city_id')
                    ->where('zip', 'like', '%' . $request['zip_code'] . '%')
                    ->orderByRaw('ABS(zip - ' . $request['zip_code'] . ')')
                    ->first();

                if ($nearestCity) {
                    $customerAddress->city_id = $nearestCity->city_id;
                } else {
                    $customerAddress->city_id = 0;
                }
            }
        } else {
            $customerAddress->city_id = 0;
        }

        $address = $request['address1'] . ', ' . $request['city'];

        // Make a request to the Google Maps Geocoding API  . ', ' . $request['zip_code']
        $response = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback');

        // Decode the JSON response
        $data = json_decode($response);

        // Check if the response contains results
        if ($data && $data->status === 'OK') {
            // Extract latitude and longitude from the response
            $latitude = $data->results[0]->geometry->location->lat;
            $longitude = $data->results[0]->geometry->location->lng;

            // Store latitude and longitude in the $customerAddress object
            $customerAddress->latitude = $latitude;
            $customerAddress->longitude = $longitude;
        } else {
            // Handle error or set default values
            $customerAddress->latitude = null;
            $customerAddress->longitude = null;
        }

        $customerAddress->save();

        $userNotes = new UserNotesCustomer();
        $userNotes->user_id = $userId;
        $userNotes->added_by = auth()->user()->id;
        $userNotes->last_updated_by = auth()->user()->id;
        $userNotes->note = $request->input('customer_notes');
        $userNotes->save();


        $tagIds = $request['tag_id'];

        if (!empty($tagIds)) {
            foreach ($tagIds as $tagId) {
                $userTag = new UserTag();
                $userTag->user_id = $userId;
                $userTag->tag_id = $tagId;
                $userTag->save();
            }
        }
        //  dd(1);
        return redirect()->route('users.index')->with('success', 'Customer created successfully');
    }


    public function show($id)
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 4;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        //$roles = Role::all();

        $commonUser = User::with('Location')->where('role', 'customer')->where('id', $id)->first();
        if (!$commonUser) {
            return view('404');
        }
        if (!$commonUser) {
            return view('404');
        }
        $customer_tag = SiteTags::all();
        $notename = DB::table('user_notes')->where(
            'user_id',
            $commonUser->id
        )->get();
        $estimates = DB::table('estimates')->where(
            'customer_id',
            $commonUser->id
        )->get();
        // $userId = $user->id;
        // $attr = [
        //     'address_primary' => '',
        //     'address_type' => '',
        //     'address_format' => ''
        // ];
        //$address = $this->getUserAddress($userId, $attr);

        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->where('user_address.user_id', $commonUser->id)
            ->get();
        //dd($userAddresscity);


        $workAddress = CustomerUserAddress::where('user_id', $commonUser->id)
            ->where('address_type', 'office')
            ->first();


        $location = CustomerUserAddress::where('user_id', $commonUser->id)->get();




        $jobasigndate = DB::table('job_assigned')
            ->where('customer_id', $commonUser->id)
            ->orderBy('created_at', 'desc')
            ->first();


        $jobasign = DB::table('jobs')
            ->where('customer_id', $commonUser->id)
            ->get();


        $payments = DB::table('payments')
            ->where('customer_id', $commonUser->id)
            ->get();



        $customerimage = DB::table('user_files')
            ->where('user_id', $commonUser->id)
            ->get();
        // dd($jobasign);

        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $payment = Payment::with('user', 'JobModel')->latest()->get();
        $UsersDetails = UsersDetails::where('user_id', $commonUser->id)->first();
        // dd($UsersDetails);

        // Fetch the $locationStates from wherever you are fetching it
        $locationStates = LocationState::all();
        // $locationStates1 = LocationState::all();

        $cities = LocationCity::all();
        // $cities1 = LocationCity::all();

        // Fetch the $leadSources from wherever you are fetching it
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all();


        $location = CustomerUserAddress::where('user_id', $commonUser->id)
            ->whereIn('address_type', ['home', 'work', 'other'])
            ->first();

        $location1 = CustomerUserAddress::where('user_id', $commonUser->id)
            ->whereIn('address_type', ['home', 'work', 'other'])
            ->where('address_id', '>', $location ? $location->address_id : 0)
            ->orderBy('address_id')
            ->first();






        $location = $commonUser->location;
        if ($location) {
            // Fetch cities associated with the technician's state
            $cities = LocationCity::where('state_id', $location->state_id)->get();
        } else {
            $cities = collect(); // No cities if no location is set
        }

        //  $location = CustomerUserAddress::where('user_id', $user->id)->get();

        //   dd($location);


        $Notes = UserNotesCustomer::where('user_id', $commonUser->id)->first();

        // Assuming you have a 'tags' relationship defined in your User model
        $userTags = $commonUser->tags;

        // Convert the comma-separated tag_id string to an array
        $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));

        $source = $commonUser->source;

        $activity = UsersActivity::with('user')
            ->where('user_id', $commonUser->id)
            ->get();
        $leadsourcename = Leadsource::where('user_id', $commonUser->id)
            ->get();
        //  dd($leadsourcename);
        $setting = UsersSettings::where('user_id', $commonUser->id)
            ->first();
        $login_history = DB::table('user_login_history')
            ->where('user_login_history.user_id', $commonUser->id)
            ->first();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $payment = Payment::with('user', 'JobModel')->latest()->get();




        $leadsource = SiteLeadSource::all();
        return view('users.show', compact('customer_tag', 'estimates', 'notename', 'leadsourcename', 'leadsource', 'selectedTags', 'tickets', 'payment', 'activity', 'setting', 'login_history', 'location1', 'tags', 'leadSources', 'cities', 'locationStates', 'commonUser', 'payments', 'payment', 'userAddresscity', 'jobasigndate', 'customerimage', 'jobasign', 'userTags', 'location', 'tickets', 'UsersDetails', 'Notes'));
    }

public function show_customers_demo_iframe($id)
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 4;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        //$roles = Role::all();

        $commonUser = User::with('Location')->where('role', 'customer')->where('id', $id)->first();
        if (!$commonUser) {
            return view('404');
        }
        if (!$commonUser) {
            return view('404');
        }
        $customer_tag = SiteTags::all();
        $notename = DB::table('user_notes')->where(
            'user_id',
            $commonUser->id
        )->get();
        $estimates = DB::table('estimates')->where(
            'customer_id',
            $commonUser->id
        )->get();
        // $userId = $user->id;
        // $attr = [
        //     'address_primary' => '',
        //     'address_type' => '',
        //     'address_format' => ''
        // ];
        //$address = $this->getUserAddress($userId, $attr);

        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->where('user_address.user_id', $commonUser->id)
            ->get();
        //dd($userAddresscity);


        $workAddress = CustomerUserAddress::where('user_id', $commonUser->id)
            ->where('address_type', 'office')
            ->first();


        $location = CustomerUserAddress::where('user_id', $commonUser->id)->get();




        $jobasigndate = DB::table('job_assigned')
            ->where('customer_id', $commonUser->id)
            ->orderBy('created_at', 'desc')
            ->first();


        $jobasign = DB::table('jobs')
            ->where('customer_id', $commonUser->id)
            ->get();


        $payments = DB::table('payments')
            ->where('customer_id', $commonUser->id)
            ->get();



        $customerimage = DB::table('user_files')
            ->where('user_id', $commonUser->id)
            ->get();
        // dd($jobasign);

        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $payment = Payment::with('user', 'JobModel')->latest()->get();
        $UsersDetails = UsersDetails::where('user_id', $commonUser->id)->first();
        // dd($UsersDetails);

        // Fetch the $locationStates from wherever you are fetching it
        $locationStates = LocationState::all();
        // $locationStates1 = LocationState::all();

        $cities = LocationCity::all();
        // $cities1 = LocationCity::all();

        // Fetch the $leadSources from wherever you are fetching it
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all();


        $location = CustomerUserAddress::where('user_id', $commonUser->id)
            ->whereIn('address_type', ['home', 'work', 'other'])
            ->first();

        $location1 = CustomerUserAddress::where('user_id', $commonUser->id)
            ->whereIn('address_type', ['home', 'work', 'other'])
            ->where('address_id', '>', $location ? $location->address_id : 0)
            ->orderBy('address_id')
            ->first();






        $location = $commonUser->location;
        if ($location) {
            // Fetch cities associated with the technician's state
            $cities = LocationCity::where('state_id', $location->state_id)->get();
        } else {
            $cities = collect(); // No cities if no location is set
        }

        //  $location = CustomerUserAddress::where('user_id', $user->id)->get();

        //   dd($location);


        $Notes = UserNotesCustomer::where('user_id', $commonUser->id)->first();

        // Assuming you have a 'tags' relationship defined in your User model
        $userTags = $commonUser->tags;

        // Convert the comma-separated tag_id string to an array
        $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));

        $source = $commonUser->source;

        $activity = UsersActivity::with('user')
            ->where('user_id', $commonUser->id)
            ->get();
        $leadsourcename = Leadsource::where('user_id', $commonUser->id)
            ->get();
        //  dd($leadsourcename);
        $setting = UsersSettings::where('user_id', $commonUser->id)
            ->first();
        $login_history = DB::table('user_login_history')
            ->where('user_login_history.user_id', $commonUser->id)
            ->first();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $payment = Payment::with('user', 'JobModel')->latest()->get();




        $leadsource = SiteLeadSource::all();
        return view('users.show_customers_demo_iframe', compact('customer_tag', 'estimates', 'notename', 'leadsourcename', 'leadsource', 'selectedTags', 'tickets', 'payment', 'activity', 'setting', 'login_history', 'location1', 'tags', 'leadSources', 'cities', 'locationStates', 'commonUser', 'payments', 'payment', 'userAddresscity', 'jobasigndate', 'customerimage', 'jobasign', 'userTags', 'location', 'tickets', 'UsersDetails', 'Notes'));
    }
    // function getUserAddress($user_id, $attr)
    // {
    //     $whereclause = " WHERE user_address.user_id = " . $user_id;

    //     if (isset($attr['address_primary']) && $attr['address_primary'] != "") {
    //         $whereclause .= " AND address_primary = '" . $attr['address_primary'] . "'";
    //     }
    //     if (isset($attr['address_type']) && $attr['address_type'] != "") {
    //         $whereclause .= " AND address_type = '" . $attr['address_type'] . "'";
    //     }

    //     $sql_address = "SELECT user_address.*,  location_states.state_name, location_states.state_code, location_cities.city
    //                 FROM `user_address`
    //                 INNER JOIN location_states ON user_address.state_id = location_states.state_id
    //                 INNER JOIN location_cities ON user_address.city = location_cities.city_id
    //                 " . $whereclause;

    //     $rs_address = DB::select($sql_address);
    //     // $address = (array) $rs_address[0];
    //     if (!empty($rs_address)) {
    //         $address = (array) $rs_address[0];
    //     } else {

    //         $address = null;
    //     }


    //     if ($address !== null) {
    //         if (isset($attr['address_format']) && $attr['address_format'] != "") {
    //             $exp1 = explode(',', $attr['address_format']);
    //             $return_addr_arr = [];

    //             foreach ($exp1 as $item) {
    //                 $return_addr_arr[] = $address[trim($item)];
    //             }

    //             $return_addr = implode(", ", $return_addr_arr);
    //         } else {
    //             //DEFAULT ADDRESS
    //             $return_addr = $address['address_line1'] . ', ' . $address['address_line2'] . ', ' . $address['city'] . ', ' . $address['zipcode'] . ', ' . $address['state_code'];
    //         }
    //     } else {
    //         // Handle the case when $address is null
    //         // For example, set $return_addr to a default value or return null
    //         $return_addr = null;
    //     }

    //     return $return_addr;
    // }


    public function edit($id)
    {
        $user = User::find($id);
        $meta = $user->meta;
        $first_name = $user->meta()->where('meta_key', 'first_name')->value('meta_value') ?? '';
        $last_name = $user->meta()->where('meta_key', 'last_name')->value('meta_value') ?? '';
        $home_phone = $user->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
        $work_phone = $user->meta()->where('meta_key', 'work_phone')->value('meta_value') ?? '';

        // Fetch the $locationStates from wherever you are fetching it
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();

        $cities = LocationCity::all();
        $cities1 = LocationCity::all();

        // Fetch the $leadSources from wherever you are fetching it
        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all();


        $location = CustomerUserAddress::where('user_id', $user->id)
            ->whereIn('address_type', ['home', 'work', 'other'])
            ->first();

        $location1 = CustomerUserAddress::where('user_id', $user->id)
            ->whereIn('address_type', ['home', 'work', 'other'])
            ->where('address_id', '>', $location ? $location->address_id : 0)
            ->orderBy('address_id')
            ->first();






        $location = $user->location;
        if ($location) {
            // Fetch cities associated with the technician's state
            $cities = LocationCity::where('state_id', $location->state_id)->get();
        } else {
            $cities = collect(); // No cities if no location is set
        }

        //  $location = CustomerUserAddress::where('user_id', $user->id)->get();

        //   dd($location);



        $Note = $user->Note;

        // Assuming you have a 'tags' relationship defined in your User model
        $userTags = $user->tags;

        // Convert the comma-separated tag_id string to an array
        $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));

        $source = $user->source;

        return view(
            'users.edit',
            compact(
                'user',
                'cities',
                'cities1',

                'meta',
                'location',
                'Note',
                'userTags',
                'selectedTags', // Pass the selectedTags to the view
                'source',
                'tags',
                'first_name',
                'last_name',
                'home_phone',
                'work_phone',
                'locationStates',
                'locationStates1',
                'leadSources',
                'cities',
                'location1'
            )
        );
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        // Validate the request
        $validator = Validator::make($request->all(), [
            // Add your validation rules here based on your update requirements
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $id,
            'mobile_phone' => 'required|max:20',
            'address1' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            // 'tag_id' => 'required',

            'zip_code' => 'max:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the user by ID
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        // Update user data
        $user->name = $request['display_name'];
        $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        $user->company = $request['company'];
        $user->user_type = $request['user_type'];
        $user->position = $request['role'];
        $user->source_id = $request['source_id'];
        if ($request->filled('password')) {
            $user->password = Hash::make($request['password']);
        }




        if ($request->hasFile('image')) {
            $directoryName = $user->id;
            $directoryPath = public_path('images/Uploads/users/' . $directoryName);
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->move($directoryPath, $imageName);
            if ($user->user_image) {
                $previousImagePath = public_path('images/Uploads/users/' . $directoryName . '/' . $user->user_image);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }
            $user->user_image = $imageName;
        }
        $user->save();

        $userDetails = UsersDetails::updateOrCreate(
            ['user_id' => $id],
            [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'home_phone' => $request->input('home_phone'),
                'work_phone' => $request->input('work_phone'),
                'additional_email' => $request->input('additional_email'),
                'customer_company' => $request->input('company'),
                'customer_type' => $request->input('user_type'),
                'customer_position' => $request->input('role'),
                'lifetime_value' => '$0.00',
                'license_number' => 0,
                // 'dob' => $request->input('dob'),
                'ssn' => 0,
                'update_done' => 'no'
            ]
        );




        // Create or update the customer address
        $customerAddress = CustomerUserAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address_line1' => $request['address1'],
                'address_line2' => $request['address_unit'],
                'address_primary' => ($request['address_type'] == 'home') ? 'yes' : 'no',
                'city' => $request['city'],
                'address_type' => $request['address_type'],
                'state_id' => $request['state_id'],
                // Fetch nearest ZIP code or use requested ZIP code if not found
                'zipcode' => DB::table('location_cities')
                    ->select('zip')
                    ->where('zip', 'like', '%' . $request['zip_code'] . '%')
                    ->orderByRaw('ABS(zip - ' . $request['zip_code'] . ')')
                    ->value('zip') ?? $request['zip_code'],
            ]
        );

        // Set city_id based on city and ZIP code
        $city = DB::table('location_cities')->where('city', $request['city'])->first();
        if ($city) {
            $matchingCity = DB::table('location_cities')->where('zip', $customerAddress->zipcode)->first();
            $customerAddress->city_id = $matchingCity ? $matchingCity->city_id : 0;
        } else {
            $nearestCity = DB::table('location_cities')
                ->select('city_id')
                ->where('zip', 'like', '%' . $customerAddress->zipcode . '%')
                ->orderByRaw('ABS(zip - ' . $customerAddress->zipcode . ')')
                ->first();
            $customerAddress->city_id = $nearestCity ? $nearestCity->city_id : 0;
        }

        // Construct the address string
        $address = $request['address1'] . ', ' . $request['city'];

        // Make a request to the Google Maps Geocoding API
        $response = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback');

        // Decode the JSON response
        $data = json_decode($response);

        // Check if the response contains results
        if ($data && $data->status === 'OK') {
            // Extract latitude and longitude from the response
            $latitude = $data->results[0]->geometry->location->lat;
            $longitude = $data->results[0]->geometry->location->lng;

            // Store latitude and longitude in the $customerAddress object
            $customerAddress->latitude = $latitude;
            $customerAddress->longitude = $longitude;
        } else {
            // Handle error or set default values
            $customerAddress->latitude = null;
            $customerAddress->longitude = null;
        }

        // Save the updated customer address
        $customerAddress->save();




        // $user->location()->updateOrCreate(
        //     ['user_id' => $id],
        //     [
        //         'address_type' => $request['address_type'],
        //         'address_line1' => $request['address1'],
        //         'address_line2' => $request['address_unit'],

        //         'city' => $request['city'],
        //         'state_id' => $request['state_id'],
        //         'zipcode' => $request['zip_code'],
        //         'address_primary' => ($request['address_type'] == 'home') ? 'yes' : 'no',
        //     ]
        // );

        /* Update or create the first location record if the required fields are not null
        if ($request['address1'] && $request['address_unit'] && $request['city'] && $request['zip_code'] && $request['state_id'] && $request['zip_code'] && $request['address_type']) {
            $user->location()

                ->updateOrCreate(
                    ['user_id' => $id, 'address_type' => $request['address_type']],
                    [
                        'address_line1' => $request['address1'],
                        'address_line2' => $request['address_unit'],
                        'city' => $request['city'],
                        'state_id' => $request['state_id'],
                        'zipcode' => $request['zip_code'],
                        'address_type' => $request['address_type'],
                    ]
                );
        }
        */
        // Update or create the second location record if the required fields are not null
        // if ($request['anotheraddress1'] && $request['anotheraddress_unit'] && $request['anothercity'] && $request['anotherzip_code'] && $request['anotherstate_id'] && $request['anotherzip_code'] && $request['anotheraddress_type']) {
        //     $user->location()
        //         ->skip(1)
        //         ->take(1)// Skip the first record
        //         ->updateOrCreate(
        //             ['user_id' => $id, 'address_type' => $request['anotheraddress_type']],
        //             [
        //                 'address_line1' => $request['anotheraddress1'],
        //                 'address_line2' => $request['anotheraddress_unit'],
        //                 'city' => $request['anothercity'],
        //                 'state_id' => $request['anotherstate_id'],
        //                 'zipcode' => $request['anotherzip_code'],
        //                 'address_type' => $request['anotheraddress_type'],
        //             ]
        //         );
        // }



        $user->Note()->updateOrCreate(
            ['user_id' => $id],
            [
                'added_by' => auth()->user()->id,
                'last_updated_by' => auth()->user()->id,
                'note' => $request['customer_notes'],
            ]
        );
        // Update user tags
        $tagIds = $request->input('tag_id', []);
        $user->tags()->detach();
        // Attach all new tags to the user
        $user->tags()->attach($tagIds);

        // Synchronize existing tags without detaching
        $user->tags()->syncWithoutDetaching($tagIds);



        return redirect()->back()->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Delete related records
            CustomerUserMeta::where('user_id', $id)->delete();
            CustomerUserAddress::where('user_id', $id)->delete();
            UserNotesCustomer::where('user_id', $id)->delete();
            UserTagIdCategory::where('user_id', $id)->delete();
            UserLeadSourceCustomer::where('user_id', $id)->delete();

            // Delete the user's image if it exists and a new image is not being uploaded
            if (empty(request()->file('image')) && !empty($user->user_image)) {
                $imagePath = public_path('images') . '/' . $user->user_image;
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $user->delete();

            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user');
        }
    }

    // Show tickets assigned to a specific user
    public function showUserTickets($userId)
    {
        $user = User::findOrFail($userId);
        $tickets = $user->tickets;
        return view('users.tickets', ['user' => $user, 'tickets' => $tickets]);
    }

    // Assign a ticket to a user
    public function assignTicket(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Assuming 'assigned_user_id' is the field to store the assigned user's ID in the ticket table
        $ticket->assigned_user_id = $request->input('user_id');
        $ticket->save();

        // Redirect or respond as needed
        return redirect()->route('tickets.show', $ticket->id);
    }

    //email send for forget password


    public function resetPassword(Request $request)
    {
        //  dd($request->all());
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // If user is not found, return redirect back with error message
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Generate a random password
        $newPassword = Str::random(10);

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        $companyName = "Frontier"; // Set the company name

        // Send email with updated password and company name
        Mail::send('emailtouser.forget_password_email', ['newPassword' => $newPassword, 'companyName' => $companyName], function ($message) use ($request) {
            $message->to($request->email)->subject('Updated Password');
            $message->from('yashdhokane890@gmail.com', 'Admin');
        });

        // Redirect back with success message
        return redirect()->back()->with('success', 'Password reset successful. Check your email for the new password.');
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('state_id');
        $cities = LocationCity::where('state_id', $stateId)->get(['city_id', 'city', 'zip']);
        return response()->json($cities);
    }

    public function getZipCode(Request $request)
    {
        $cityId = $request->input('city_id');
        $cityName = $request->input('city_name');

        // Fetch the city details based on city ID and city name
        $city = LocationCity::where('city_id', $cityId)->where('city', $cityName)->first();

        if ($city) {
            // If city found, return the zip code
            return response()->json(['zip_code' => $city->zip]);
        } else {
            // If city not found, return an error or appropriate response
            return response()->json(['error' => 'City not found'], 404);
        }
    }
    public function getCitiesanother(Request $request)
    {
        $stateId = $request->input('anotherstate_id');
        $cities = LocationCity::where('state_id', $stateId)->distinct()->get(['city_id', 'city', 'zip']);
        return response()->json($cities);
    }

    public function getZipCodeanother(Request $request)
    {
        $cityId = $request->input('anothercity_id');
        $cityName = $request->input('anothercity_name');

        // Fetch the city details based on city ID and city name
        $city = LocationCity::where('city_id', $cityId)->where('city', $cityName)->first();

        if ($city) {
            // If city found, return the zip code
            return response()->json(['anotherzip_code' => $city->zip]);
        } else {
            // If city not found, return an error or appropriate response
            return response()->json(['error' => 'City not found'], 404);
        }
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'password' => 'required|',
        ]);

        $user = User::findOrFail($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }


    public function customer_schedule(Request $request)
    {

        try {


            $validator = Validator::make($request->all(), [

                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'display_name' => 'required|string|max:255',
                //'email' => 'required|email|unique:users,email',
                'mobile_phone' => 'required|max:20',
                'address1' => 'required',
                'city' => 'required',
                'state_id' => 'required',
                'zip_code' => 'max:10',
            ]);




            if ($validator->fails()) {

                return response()->json([
                    'status' => false,
                    'message' => $validator,
                ]);
            }




            $user = new User();
            $user->name = $request['display_name'];
            $user->email = $request['email'];
            $user->mobile = $request['mobile_phone'];
            // $user->company = $request['company'];
            // $user->user_type = $request['user_type'];
            //  $user->position = $request['role'];
            $user->source_id = $request['source_id'];
            $user->customer_id = 0;
            $user->password = Hash::make($request['password']);

            $user->save();
            $userId = $user->id;

            if ($request->hasFile('image')) {
                $directoryName = $userId;
                $directoryPath = public_path('images/Uploads/users/' . $directoryName);

                // Ensure the directory exists; if not, create it
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                }

                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $image->move($directoryPath, $imageName);

                $user->user_image = $imageName;
                $user->save();
            }

            $userId = $user->id;

            $usersDetails = new UsersDetails();
            $usersDetails->user_id = $userId;
            $usersDetails->unique_number = 0;
            $usersDetails->lifetime_value = 0;
            $usersDetails->license_number = 0;
            // $usersDetails->dob = 0;
            $usersDetails->ssn = 0;
            $usersDetails->update_done = 'no';


            $usersDetails->first_name = $request->input('first_name');
            $usersDetails->last_name = $request->input('last_name');
            $usersDetails->home_phone = $request->input('home_phone');
            $usersDetails->work_phone = $request->input('work_phone');
            $usersDetails->customer_position = $request->input('role');

            $usersDetails->additional_email = $request->input('additional_email');

            $usersDetails->customer_company = $request->input('company');

            $usersDetails->customer_type = $request->input('user_type');

            $usersDetails->save();

            $stateName = LocationCity::where('state_id', $request['state_id'])->first();

            $customerAddress = new CustomerUserAddress();
            $customerAddress->user_id = $userId;
            $customerAddress->address_line1 = $request['address1'];
            $customerAddress->address_line2 = $request['address_unit'];
            $customerAddress->address_primary = ($request['address_type'] == 'home') ? 'yes' : 'no';
            $customerAddress->city = $request['city'];
            $customerAddress->state_name = $stateName->state_code;
            $customerAddress->address_type = $request['address_type'];
            $customerAddress->state_id = $request['state_id'];
            $nearestZip = DB::table('location_cities')
                ->select('zip')
                ->where('zip', 'like', '%' . $request['zip_code'] . '%')

                ->orderByRaw('ABS(zip - ' . $request['zip_code'] . ')')
                ->first();

            // If a nearest ZIP code is found, use it; otherwise, use the provided ZIP code
            $customerAddress->zipcode = $nearestZip ? $nearestZip->zip : $request['zip_code'];

            $city = DB::table('location_cities')->where('city', $request['city'])->first();

            if ($city) {
                $matchingCity = DB::table('location_cities')->where('zip', $request['zip_code'])->first();

                if ($matchingCity) {
                    $customerAddress->city_id = $matchingCity->city_id;
                } else {
                    $nearestCity = DB::table('location_cities')
                        ->select('city_id')
                        ->where('zip', 'like', '%' . $request['zip_code'] . '%')
                        ->orderByRaw('ABS(zip - ' . $request['zip_code'] . ')')
                        ->first();

                    if ($nearestCity) {
                        $customerAddress->city_id = $nearestCity->city_id;
                    } else {
                        $customerAddress->city_id = 0;
                    }
                }
            } else {
                $customerAddress->city_id = 0;
            }

            $address = $request['address1'] . ', ' . $request['city'];

            // Make a request to the Google Maps Geocoding API  . ', ' . $request['zip_code']
            $response = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback');

            // Decode the JSON response
            $data = json_decode($response);

            // Check if the response contains results
            if ($data && $data->status === 'OK') {
                // Extract latitude and longitude from the response
                $latitude = $data->results[0]->geometry->location->lat;
                $longitude = $data->results[0]->geometry->location->lng;

                // Store latitude and longitude in the $customerAddress object
                $customerAddress->latitude = $latitude;
                $customerAddress->longitude = $longitude;
            } else {
                // Handle error or set default values
                $customerAddress->latitude = null;
                $customerAddress->longitude = null;
            }

            $customerAddress->save();

            $userNotes = new UserNotesCustomer();
            $userNotes->user_id = $userId;
            $userNotes->added_by = auth()->user()->id;
            $userNotes->last_updated_by = auth()->user()->id;
            $userNotes->note = $request->input('customer_notes');
            $userNotes->save();


            $tagIds = $request['tag_id'];

            if (!empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $userTag = new UserTag();
                    $userTag->user_id = $userId;
                    $userTag->tag_id = $tagId;
                    $userTag->save();
                }
            }




            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
            ]);
        }
    }



    //  public function checkMobile(Request $request)
    // {
    //     $mobileNumber = $request->input('mobile_number');

    //     // Check if the mobile number exists in the users table
    //     $user = User::where('mobile', $mobileNumber)->first();

    //     // Prepare the response
    //     $response = [
    //         'exists' => $user ? true : false,
    //         'user' => $user // Include the user details in the response
    //     ];

    //     return response()->json($response);
    // }

    public function get_number_customer_one(Request $request)
    {
        $phone = $request->phone;
        $customers = '';

        if (isset($phone) && !empty($phone)) {
            $filterCustomer = User::where('mobile', $phone)
                ->where('role', 'customer')
                ->get();

            if (isset($filterCustomer) && $filterCustomer->count() > 0) {
                foreach ($filterCustomer as $key => $value) {
                    $getCustomerAddress = DB::table('user_address')
                        ->select('user_address.city', 'location_states.state_name', 'user_address.zipcode')
                        ->join('location_states', 'location_states.state_id', 'user_address.state_id')
                        ->where('user_id', $value->id)
                        ->first();

                    $editRoute = route('users.show', ['id' => $value->id]);

                    // Define $imageSrc here (assuming it represents the user's image source)
                    $imagePath = public_path('images/Uploads/users/' . $value->user_image);
                    if (file_exists($imagePath) && !empty($value->user_image)) {
                        $imageSrc = asset('public/images/Uploads/users') . '/' . $value->user_image;
                    } else {
                        $imageSrc = asset('public/images/login_img_bydefault.png');
                    }

                    $customers .= '<a href="' . $editRoute . '">'; // Start anchor tag
                    $customers .= '<h5 class="font-weight-medium mb-2">Select Customer
                                    </h5><div class="customer_sr_box selectCustomer2 px-0" data-id="' . $value->id . '" data-name="' . $value->name . '"><div class="row justify-content-around"><div class="col-md-2 d-flex align-items-center"><span>';
                    $customers .= '<img src="' . $imageSrc . '" alt="user" class="rounded-circle" width="50">';
                    $customers .= '</span></div><div class="col-md-9"><h6 class="font-weight-medium mb-0">' . $value->name . ' ';
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city)) {
                        $customers .= '<small class="text-muted">' . $getCustomerAddress->city . ' Area</small>';
                    }
                    $customers .= '</h6><p class="text-muted test">' . $value->mobile . ' / ' . $value->email . '';
                    if (isset($getCustomerAddress->city) && !empty($getCustomerAddress->city) && isset($getCustomerAddress->state_name) && !empty($getCustomerAddress->state_name) && isset($getCustomerAddress->zipcode) && !empty($getCustomerAddress->zipcode)) {
                        $customers .= '<br />' . $getCustomerAddress->city . ', ' . $getCustomerAddress->state_name . ', ' . $getCustomerAddress->zipcode . '';
                    }
                    $customers .= '</p></div></div></div>';
                    $customers .= '</a>'; // End anchor tag
                }
            }
        }

        return ['customers' => $customers];
    }
    public function getUserStatus(Request $request)
    {

        $userId = $request->user_id;
        // dd($request->user_id);
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $status = $user->status;

        return response()->json(['status' => $status]);
    }

    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $cities = DB::table('location_cities')
            ->selectRaw('DISTINCT city')
            //  ->distinct()
            ->where('city', 'like', '%' . $term . '%')
            ->groupBy('city')
            ->take(10)
            ->get();
        return response()->json($cities);
    }

    public function customer_tags_store(Request $request)
    {
        // Validate request data
        $request->validate([]);

        $userId = $request->input('id');
        $tagIds = $request->input('customer_tags');

        try {
            // Save tags for the user
            if (!empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $userTag = new UserTag();
                    $userTag->user_id = $userId;
                    $userTag->tag_id = $tagId;
                    $userTag->save();
                }
            }

            return redirect()->back()->with('success', 'Tags created successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during database operation
            return redirect()->back()->with('error', 'Failed to create tags: ' . $e->getMessage());
        }
    }

    public function customer_file_store(Request $request)
    {
        // Validate request data
        $request->validate([]);

        $userId = $request->input('id');

        // Create a new instance of UserFiles model
        $file = new UserFiles();

        // Process file upload
        if ($request->hasFile('attachment')) {
            $uploadedFile = $request->file('attachment');

            // Check if the file upload was successful
            if ($uploadedFile->isValid()) {
                // Generate a unique filename
                $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                $imageName1 = $uploadedFile->getClientOriginalName();

                // Construct the full path for the directory
                $directoryPath = public_path('images/users/' . $userId);

                // Ensure the directory exists; if not, create it
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true);
                }

                // Move the uploaded file to the unique directory
                if ($uploadedFile->move($directoryPath, $imageName1)) {
                    // Save file details to the database
                    $file->user_id = $userId;
                    $file->filename = $imageName1;
                    $file->path = $directoryPath;
                    $file->type = $uploadedFile->getClientMimeType();
                    // $file->size = $uploadedFile->getSize(); // Add file size
                    $file->storage_location = 'local'; // Assuming storage location is local
                    $file->save();

                    // Redirect with success message
                    return redirect()->back()->with('success', 'Attachment added successfully');
                } else {
                    // Error: Failed to move uploaded file
                    return redirect()->back()->with('error', 'Failed to move uploaded file');
                }
            } else {
                // Error: Invalid file uploaded
                return redirect()->back()->with('error', 'Invalid file uploaded');
            }
        } else {
            // Error: No file uploaded
            return redirect()->back()->with('error', 'No file uploaded');
        }
    }

    public function customer_leadsource_store(Request $request)
    {
        // Validate request data
        $request->validate([]);

        $userId = $request->input('id');
        $tagIds = $request->input('lead_source');

        try {
            // Save tags for the user
            if (!empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $userTag = new Leadsource();
                    $userTag->user_id = $userId;
                    $userTag->source_name = $tagId;
                    $userTag->added_by = Auth::id();
                    $userTag->last_updated_by = Auth::id();


                    $userTag->save();
                }
            }

            return redirect()->back()->with('success', 'Leadsource created successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during database operation
            return redirect()->back()->with('error', 'Failed to create Leadsource: ' . $e->getMessage());
        }
    }

    public function customercomment(Request $request)
    {


        $addedByUserId = auth()->user()->id;
        $user = User::findOrFail($request->user_id);

        $note = new UserNotesCustomer();
        $note->user_id = $user->id;
        $note->added_by = $addedByUserId;
        $note->last_updated_by = $addedByUserId;
        $note->note = $request->note;

        if (!empty($request->note)) {
            $note->save();
        }

        if ($request->is_updated === null) {
            $user->is_updated = 'no';
        } else {
            $isUpdated = $request->is_updated;
            $user->is_updated = $isUpdated;
        }

        $user->updated_by = $addedByUserId;
        $user->updated_at = now();
        $user->save();

        return redirect()->back()->with('success', 'Work details updated successfully');
    }
}