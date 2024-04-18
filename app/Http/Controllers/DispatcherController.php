<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\UsersDetails;


use App\Models\UserTag;
use App\Models\SiteTags;
use App\Models\Technician;
use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\CustomerUserMeta;
use App\Models\CustomerUserAddress;
use Illuminate\Support\Facades\Validator;

class DispatcherController extends Controller
{
    public function index()
    {

        $users = User::where('role', 'dispatcher')->orderBy('created_at', 'desc') // Assuming 'created_at' is a timestamp column
            ->get();

        return view('dispatcher.index', compact('users'));
    }

    public function create()
    {
        $permissions = DB::table('user_permissions')->pluck('permission_id')->toArray();

        $users = User::all();
        //    $roles = Role::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all(); // Fetch all tags


        return view('dispatcher.create', compact('permissions', 'users', 'tags', 'locationStates'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
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
        $user->role = $request['role'];
        $permissionsString = json_encode($request->input('permissions'));
        $user->permissions = $permissionsString;
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


        $usersDetails->save();
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
        return redirect()->route('dispatcher.index')->with('success', 'Dispatcher created successfully');
    }



    public function show($id)
    {
        $dispatcher = User::find($id);
        // dd($dispatcher);
        $notename = DB::table('user_notes')->where(
            'user_id',
            $dispatcher->id
        )->get();
        $meta = $dispatcher->meta;
        $jobasign = DB::table('jobs')
            ->where('added_by', $dispatcher->id)
            ->get();
        $activity = DB::table('job_activity')
            ->where('user_id', $dispatcher->id)
            ->get();

        $home_phone = $dispatcher->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
        // $userAddresscity = DB::table('user_address')
        //     ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
        //     ->where('user_address.user_id', $dispatcher->id)
        //     ->value('location_cities.city');
        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->where('user_address.user_id', $dispatcher->id)
            ->get();
        $latitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $dispatcher->id)
            ->value('location_cities.latitude');
        $longitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $dispatcher->id)
            ->value('location_cities.longitude');

        $location = CustomerUserAddress::where('user_id', $dispatcher->id)->get();




        return view('dispatcher.show', compact('dispatcher', 'notename', 'activity', 'jobasign', 'location', 'latitude', 'longitude', 'userAddresscity', 'home_phone'));
    }


    public function edit(string $id)
    {
        $dispatcher = User::find($id);
        $locationStates = LocationState::all();


        // Check if $user is found
        if (!$dispatcher) {
            // Handle the case where the user is not found, perhaps redirect to an error page
            return redirect()->route('dispatcher.index');
        }

        // Check if $user has meta relationship
        $meta = $dispatcher->meta;
        if ($meta) {
            $first_name = $dispatcher->meta()->where('meta_key', 'first_name')->value('meta_value') ?? '';
            $last_name = $dispatcher->meta()->where('meta_key', 'last_name')->value('meta_value') ?? '';
            $home_phone = $dispatcher->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
            $work_phone = $dispatcher->meta()->where('meta_key', 'work_phone')->value('meta_value') ?? '';

            $location = $dispatcher->location;
            $Note = $dispatcher->Note;
            $source = $dispatcher->source;

            $tags = SiteTags::all();
            $permissions = DB::table('permissions')->pluck('name')->toArray();



            // Assuming you have a 'tags' relationship defined in your User model
            $userTags = $dispatcher->tags;

            // Convert the comma-separated tag_id string to an array
            $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));

            return view('dispatcher.edit', compact('dispatcher', 'permissions', 'locationStates', 'userTags', 'selectedTags', 'meta', 'location', 'Note', 'tags', 'source', 'first_name', 'last_name', 'home_phone', 'work_phone'));
        } else {
            // Handle the case where meta is not found, perhaps redirect to an error page
            return redirect()->route('dispatcher.index');
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
            // 'tag_id' => 'required',

            'state_id' => 'required',
            'zip_code' => 'max:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('dispatcher.index')->with('error', 'Dispatcher not found');
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
        // $user->meta()->updateOrCreate(
        //     ['meta_key' => 'first_name'],
        //     ['meta_value' => $request['first_name']]
        // );

        // $user->meta()->updateOrCreate(
        //     ['meta_key' => 'last_name'],
        //     ['meta_value' => $request['last_name']]
        // );

        // $user->meta()->updateOrCreate(
        //     ['meta_key' => 'home_phone'],
        //     ['meta_value' => $request['home_phone']]
        // );

        // $user->meta()->updateOrCreate(
        //     ['meta_key' => 'work_phone'],
        //     ['meta_value' => $request['work_phone']]
        // );
         $userDetails = UsersDetails::updateOrCreate(
            ['user_id' => $id],
            [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'home_phone' => $request->input('home_phone'),
                'work_phone' => $request->input('work_phone'),

                'lifetime_value' => '$0.00',
                'license_number' => 0,
                // 'dob' => $request->input('dob'),
                'ssn' => 0,
                'update_done' => 'no'
            ]
        );
        $userDetails->save();


        // Update user address

        
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
      


        $tagIds = $request->input('tag_id', []);
        $user->tags()->detach();
        $user->tags()->attach($tagIds);
        $user->tags()->syncWithoutDetaching($tagIds);

        return redirect()->route('dispatcher.index')->with('success', 'Dispatcher updated successfully');
    }



    // public function technicianGet()
    // {
    //     $technicians = Technician::get();
    //     return view('technicians.technician_loactor', compact('technicians'));
    // }


}