<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;


use App\Models\User;
use App\Models\UsersSettings;
use App\Models\UsersDetails;


use App\Models\UserTag;
use App\Models\Payment;
use App\Models\JobModel;
use App\Models\SiteTags;
use Illuminate\Support\Facades\DB;

use App\Models\Technician;
use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\CustomerUserMeta;
use App\Models\CustomerUserAddress;
use Illuminate\Support\Facades\Validator;




class MultiAdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')->orderBy('name', 'asc') // Assuming 'created_at' is a timestamp column
            ->get();

        return view('multiadmin.index', compact('users'));
    }

    public function create()
    {
        $users = User::all();
        //    $roles = Role::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all(); // Fetch all tags


        return view('multiadmin.create', compact('users', 'tags', 'locationStates'));
    }

   public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
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
        $user->employee_id = User::max('employee_id') + 1;

        $user->mobile = $request['mobile_phone'];
        $user->role = $request['role'];
        $user->password = Hash::make($request['password']);
        $user->save();
        $userId = $user->id;

        if ($request->hasFile('image')) {
            // Generate a unique directory name based on user ID and timestamp
            $directoryName = $userId;

            // Construct the full path for the directory
            $directoryPath = public_path('images/Uploads/users/' . $directoryName);

            // Ensure the directory exists; if not, create it
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Move the uploaded image to the unique directory
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName(); // Or generate a unique name if needed
            $image->move($directoryPath, $imageName);

            // Save the image path in the user record
            $user->user_image = $imageName;
            $user->save();
        }

        // $userId = $user->id;
        // $currentTimestamp = now();

        // $userMeta = [
        //     ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $request['first_name']],
        //     ['user_id' => $userId, 'meta_key' => 'last_name', 'meta_value' => $request['last_name']],
        //     ['user_id' => $userId, 'meta_key' => 'home_phone', 'meta_value' => $request['home_phone']],
        //     ['user_id' => $userId, 'meta_key' => 'work_phone', 'meta_value' => $request['work_phone']],
        //     ['user_id' => $userId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
        //     ['user_id' => $userId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        // ];

        // CustomerUserMeta::insert($userMeta);

        $usersDetails = new UsersDetails();
        $usersDetails->user_id = $userId;
        // $usersDetails->unique_number = 0;
        // $usersDetails->lifetime_value = 0;
        // $usersDetails->license_number = 0;
        // $usersDetails->dob = 0;
        // $usersDetails->ssn = 0;
        $usersDetails->update_done = 'no';


        $usersDetails->first_name = $request->input('first_name');
        $usersDetails->last_name = $request->input('last_name');
        $usersDetails->home_phone = $request->input('home_phone');
        $usersDetails->work_phone = $request->input('work_phone');
        // $usersDetails->customer_position = $request->input('role');

        // $usersDetails->additional_email = $request->input('additional_email');

        // $usersDetails->customer_company = $request->input('company');

        // $usersDetails->customer_type = $request->input('user_type');

        $usersDetails->save();

        if ($request->filled('city')) {
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


            // Construct the address string
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

            // dd($customerAddress->latitude, $customerAddress->longitude);
            // dd($request->all());

            $customerAddress->save();


        }

        $tagIds = $request['tag_id'];

        if (!empty($tagIds)) {
            foreach ($tagIds as $tagId) {
                $userTag = new UserTag();
                $userTag->user_id = $userId;
                $userTag->tag_id = $tagId;
                $userTag->save();
            }
        }


        //    dd("end");
        return redirect()->route('multiadmin.index')->with('success', 'Admin created successfully');
    }



     public function show($id)
    {

        $multiadmin = User::find($id);
        if (!$multiadmin) {
            return view('404');
        }


        $notename = DB::table('user_notes')->where(
            'user_id',
            $multiadmin->id
        )->get();
        $meta = $multiadmin->meta;
        $jobasign = DB::table('jobs')
            ->where('added_by', $multiadmin->id)
            ->get();
        $activity = DB::table('job_activity')
            ->where('user_id', $multiadmin->id)
            ->get();
        $home_phone = $multiadmin->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
        // $userAddresscity = DB::table('user_address')
        //     ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
        //     ->where('user_address.user_id', $multiadmin->id)
        //     ->value('location_cities.city');
        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->where('user_address.user_id', $multiadmin->id)
            ->get();

        $latitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $multiadmin->id)
            ->value('location_cities.latitude');
        $longitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $multiadmin->id)
            ->value('location_cities.longitude');
        $location = CustomerUserAddress::where('user_id', $multiadmin->id)->get();

        $customerimage = DB::table('user_files')
            ->where('user_id', $multiadmin->id)
            ->get();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        $payment = Payment::whereHas('JobModel', function ($query) use ($multiadmin) {
            $query->where('added_by', $multiadmin->id);
        })
            ->latest()
            ->get();

        $setting = UsersSettings::
            where('user_id', $multiadmin->id)
            ->first();

        $UsersDetails = UsersDetails::where('user_id', $multiadmin->id)->first();

        $locationStates = LocationState::all();
        $location = $multiadmin->location;
        $Note = $multiadmin->Note;
        $source = $multiadmin->source;

        $tags = SiteTags::all();

        $userTags = $multiadmin->tags;

        $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));
        $jobActivity = DB::table('job_activity')
            ->select(
                'job_activity.user_id',
                'job_activity.activity',
                'job_activity.created_at as activity_date',
                DB::raw("'job_activity' as activity_type"),
                'users.*' // Select all columns from the users table
            )
            ->join('users', 'job_activity.user_id', '=', 'users.id')
            ->where('job_activity.user_id', $multiadmin->id);

        $userActivity = DB::table('user_activity') // Corrected table name
            ->select(
                'user_activity.user_id',
                'user_activity.activity',
                'user_activity.created_at as activity_date',
                DB::raw("'user_activity' as activity_type"),
                'users.*' // Select all columns from the users table
            )
            ->join('users', 'user_activity.user_id', '=', 'users.id')
            ->where('user_activity.user_id', $multiadmin->id);

        $activity = $jobActivity->union($userActivity)
            ->orderBy('activity_date', 'desc') // Order by created_at in descending order
            ->get();

        return view('multiadmin.show', compact('UsersDetails', 'activity', 'Note', 'source', 'multiadmin', 'tags', 'userTags', 'selectedTags', 'locationStates', 'setting', 'payment', 'tickets', 'customerimage', 'notename', 'activity', 'jobasign', 'longitude', 'latitude', 'userAddresscity', 'location', 'home_phone'));
    }



    public function edit(string $id)
    {
        $multiadmin = User::find($id);
        $locationStates = LocationState::all();


        // Check if $user is found
        if (!$multiadmin) {
            // Handle the case where the user is not found, perhaps redirect to an error page
            return redirect()->route('multiadmin.index');
        }

        // Check if $user has meta relationship
        $meta = $multiadmin->meta;
        if ($meta) {
            $first_name = $multiadmin->meta()->where('meta_key', 'first_name')->value('meta_value') ?? '';
            $last_name = $multiadmin->meta()->where('meta_key', 'last_name')->value('meta_value') ?? '';
            $home_phone = $multiadmin->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
            $work_phone = $multiadmin->meta()->where('meta_key', 'work_phone')->value('meta_value') ?? '';

            $location = $multiadmin->location;
            $Note = $multiadmin->Note;
            $source = $multiadmin->source;

            $tags = SiteTags::all();



            // Assuming you have a 'tags' relationship defined in your User model
            $userTags = $multiadmin->tags;

            // Convert the comma-separated tag_id string to an array
            $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));

            return view('multiadmin.edit', compact('multiadmin', 'locationStates', 'userTags', 'selectedTags', 'meta', 'location', 'Note', 'tags', 'source', 'first_name', 'last_name', 'home_phone', 'work_phone'));
        } else {
            // Handle the case where meta is not found, perhaps redirect to an error page
            return redirect()->route('multiadmin.index');
        }
    }
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $id,
            'mobile_phone' => 'required|max:20',
            'address1' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip_code' => 'max:10',
            //  'tag_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('multiadmin.index')->with('error', 'Admin not found');
        }

        $user->name = $request['display_name'];
        // $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        $user->role = $request['role'];
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


        // Update user meta
        $userDetails = UsersDetails::updateOrCreate(
            ['user_id' => $id],
            [
                'first_name' => $request->input('first_name'),
                'additional_email' => $request->input('additional_email'),

                'last_name' => $request->input('last_name'),
                'home_phone' => $request->input('home_phone'),
                'work_phone' => $request->input('work_phone'),

                'lifetime_value' => '$0.00',
                'license_number' => $request->input('license_number'),
                'dob' => $request->input('dob'),
                'ssn' => $request->input('ssn'),
                'update_done' => 'no'
            ]
        );
        $userDetails->save();
        // Update user address

        if ($request->filled('city')) {
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
        }


        // Update user notes

        $tagIds = $request->input('tag_id', []);
        $user->tags()->detach();
        $user->tags()->attach($tagIds);
        $user->tags()->syncWithoutDetaching($tagIds);


        return redirect()->back()->with('success', 'Admin updated successfully');
    }



    // public function technicianGet()
    // {
    //     $technicians = Technician::get();
    //     return view('technicians.technician_loactor', compact('technicians'));
    // }


}
