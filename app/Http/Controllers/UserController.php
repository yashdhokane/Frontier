<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\LocationCity;

use App\Models\JobModel;

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
use Illuminate\Support\Facades\Validator;


use Exception;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->get();
        // Pass users and user addresses to the view
        return view('users.index', ['users' => $users]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $roles = Role::all();
        $locationStates = LocationState::all();
        $locationStates1 = LocationState::all();

        $leadSources = SiteLeadSource::all();
        $tags = SiteTags::all(); // Fetch all tags

        // Fetch all cities initially
        // $cities = LocationCity::all();
        // $cities1 = LocationCity::all();


        return view('users.create', compact('users', 'roles', 'locationStates', 'locationStates1', 'leadSources', 'tags'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

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
        $user->company = $request['company'];
        $user->user_type = $request['user_type'];
        $user->position = $request['role'];
        $user->source_id = $request['source_id'];
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
        $currentTimestamp = now();

        $user->meta()->createMany([
            ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $request['first_name']],
            ['user_id' => $userId, 'meta_key' => 'last_name', 'meta_value' => $request['last_name']],
            ['user_id' => $userId, 'meta_key' => 'home_phone', 'meta_value' => $request['home_phone']],
            ['user_id' => $userId, 'meta_key' => 'work_phone', 'meta_value' => $request['work_phone']],
            ['user_id' => $userId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
            ['user_id' => $userId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        ]);








        // For the first address
        if ($request->filled('address_type') && $request->filled('city')) {
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

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }



    public function show($id)
    {
        $roles = Role::all();
        $user = User::with('Note')->find($id);
        $userId = $user->id;
        $attr = [
            'address_primary' => '',
            'address_type' => '',
            'address_format' => ''
        ];
        $address = $this->getUserAddress($userId, $attr);

        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->where('user_address.user_id', $user->id)
            ->get();
        //dd($userAddresscity);


        $workAddress = CustomerUserAddress::where('user_id', $user->id)
            ->where('address_type', 'office')
            ->first();


        $location = CustomerUserAddress::where('user_id', $user->id)->get();



        $meta = $user->meta;
        $home_phone = $user->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';


        $jobasigndate = DB::table('job_assigned')
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();


        $jobasign = DB::table('jobs')
            ->where('customer_id', $user->id)
            ->get();


        $payments = DB::table('payments')
            ->where('customer_id', $user->id)
            ->get();



        $customerimage = DB::table('user_files')
            ->where('user_id', $user->id)
            ->get();
        // dd($jobasign);





        return view('users.show', compact('user', 'address', 'payments', 'userAddresscity', 'jobasigndate', 'customerimage', 'jobasign', 'roles', 'home_phone', 'location'));
    }

    function getUserAddress($user_id, $attr)
    {
        $whereclause = " WHERE user_address.user_id = " . $user_id;

        if (isset($attr['address_primary']) && $attr['address_primary'] != "") {
            $whereclause .= " AND address_primary = '" . $attr['address_primary'] . "'";
        }
        if (isset($attr['address_type']) && $attr['address_type'] != "") {
            $whereclause .= " AND address_type = '" . $attr['address_type'] . "'";
        }

        $sql_address = "SELECT user_address.*,  location_states.state_name, location_states.state_code, location_cities.city
                    FROM `user_address`
                    INNER JOIN location_states ON user_address.state_id = location_states.state_id
                    INNER JOIN location_cities ON user_address.city = location_cities.city_id
                    " . $whereclause;

        $rs_address = DB::select($sql_address);
        // $address = (array) $rs_address[0];
        if (!empty($rs_address)) {
            $address = (array) $rs_address[0];
        } else {

            $address = null;
        }


        if ($address !== null) {
            if (isset($attr['address_format']) && $attr['address_format'] != "") {
                $exp1 = explode(',', $attr['address_format']);
                $return_addr_arr = [];

                foreach ($exp1 as $item) {
                    $return_addr_arr[] = $address[trim($item)];
                }

                $return_addr = implode(", ", $return_addr_arr);
            } else {
                //DEFAULT ADDRESS
                $return_addr = $address['address_line1'] . ', ' . $address['address_line2'] . ', ' . $address['city'] . ', ' . $address['zipcode'] . ', ' . $address['state_code'];
            }
        } else {
            // Handle the case when $address is null
            // For example, set $return_addr to a default value or return null
            $return_addr = null;
        }

        return $return_addr;
    }


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

        // Update user meta
        $user->meta()->updateOrCreate(
            ['meta_key' => 'first_name'],
            ['meta_value' => $request['first_name']]
        );

        $user->meta()->updateOrCreate(
            ['meta_key' => 'last_name'],
            ['meta_value' => $request['last_name']]
        );

        $user->meta()->updateOrCreate(
            ['meta_key' => 'home_phone'],
            ['meta_value' => $request['home_phone']]
        );

        $user->meta()->updateOrCreate(
            ['meta_key' => 'work_phone'],
            ['meta_value' => $request['work_phone']]
        );
        $customerAddress = CustomerUserAddress::findOrFail($id);

        if ($request->filled('address_type') && $request->filled('city')) {
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



        return redirect()->route('users.index')->with('success', 'User updated successfully');
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

            // Validate the request

            $validator = Validator::make($request->all(), [



                'first_name' => 'required|max:255',

                'last_name' => 'required|max:255',

                'display_name' => 'required|string|max:255',

                'email' => 'required|email|unique:users,email',

                'mobile_phone' => 'required|max:20',

                // 'home_phone' => 'required|max:20',

                // 'work_phone' => 'required|max:20',





                'address1' => 'required',

                // 'address_unit' => 'required',



                'city' => 'required',

                'state_id' => 'required',

                'zip_code' => 'max:10',

                // 'customer_notes' => 'required',

                // 'customer_tags' => 'required', // Update the maximum length as needed

                // 'source_id' => 'required',

                // 'image' => 'required',

                // 'user_type' => 'required',

                // 'company' => 'required',

                // 'role' => 'required',

            ]);



            if ($validator->fails()) {

                return response()->json([
                    'status' => false,
                    'message' => $validator,
                ]);
            }



            // If validation passes, create the user and related records

            $user = new User();

            $user->name = $request['display_name'];

            $user->email = $request['email'];

            $user->mobile = $request['mobile_phone'];

            $user->company = $request['company'];

            $user->user_type = $request['user_type'];

            $user->position = $request['role'];

            $user->source_id = $request['source_id'];

            $user->password = Hash::make($request['password']);




            $user->save();
            $userId = $user->id;

            if ($request->hasFile('image')) {
                // Generate a unique directory name based on user ID and timestamp
                $directoryName = $userId;

                // Construct the full path for the directory
                $directoryPath = public_path('images/customer/' . $directoryName);

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



            $currentTimestamp = now();



            $user->meta()->createMany([

                ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $request['first_name']],

                ['user_id' => $userId, 'meta_key' => 'last_name', 'meta_value' => $request['last_name']],

                ['user_id' => $userId, 'meta_key' => 'home_phone', 'meta_value' => $request['home_phone']],

                ['user_id' => $userId, 'meta_key' => 'work_phone', 'meta_value' => $request['work_phone']],

                ['user_id' => $userId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],

                ['user_id' => $userId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],

            ]);



            // For the first address

            if ($request->filled('address_type') && $request->filled('city')) {

                $customerAddress = new CustomerUserAddress();

                $customerAddress->user_id = $userId;

                $customerAddress->address_line1 = $request['address1'];

                $customerAddress->address_line2 = $request['address_unit'];

                $customerAddress->city = $request['city'];

                $customerAddress->address_type = $request['address_type'];

                $customerAddress->state_id = $request['state_id'];

                $customerAddress->zipcode = $request['zip_code'];

                $customerAddress->save();
            }



            // For the second address

            // if ($request->filled('anotheraddress_type') && $request->filled('anothercity')) {

            //     $customerAddress = new CustomerUserAddress();

            //     $customerAddress->user_id = $userId;

            //     $customerAddress->address_line1 = $request['anotheraddress1'];

            //     $customerAddress->address_line2 = $request['anotheraddress_unit'];

            //     $customerAddress->city = $request['anothercity'];

            //     $customerAddress->address_type = $request['anotheraddress_type'];

            //     $customerAddress->state_id = $request['anotherstate_id'];

            //     $customerAddress->zipcode = $request['anotherzip_code'];

            //     $customerAddress->save();
            // }





            $userNotes = new UserNotesCustomer();

            $userNotes->user_id = $userId;

            $userNotes->note = $request['customer_notes'];

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
            $filterCustomer = User::where('mobile', 'LIKE', '%' . $phone . '%')
                ->where('role', 'customer')
                ->get();

            if (isset($filterCustomer) && $filterCustomer->count() > 0) {
                foreach ($filterCustomer as $key => $value) {
                    $getCustomerAddress = DB::table('user_address')
                        ->select('user_address.city', 'location_states.state_name', 'user_address.zipcode')
                        ->join('location_states', 'location_states.state_id', 'user_address.state_id')
                        ->where('user_id', $value->id)
                        ->first();

                    $editRoute = route('users.edit', ['id' => $value->id]);

                    // Define $imageSrc here (assuming it represents the user's image source)
                    $imagePath = public_path('images/Uploads/users/' . $value->user_image);
                    if (file_exists($imagePath) && !empty($value->user_image)) {
                        $imageSrc = asset('public/images/Uploads/users') . '/' . $value->user_image;
                    } else {
                        $imageSrc = asset('public/images/login_img_bydefault.png');
                    }

                    $customers .= '<a href="' . $editRoute . '">'; // Start anchor tag
                    $customers .= '<div class="customer_sr_box selectCustomer2 px-0" data-id="' . $value->id . '" data-name="' . $value->name . '"><div class="row justify-content-around"><div class="col-md-2 d-flex align-items-center"><span>';
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
            ->distinct()
            ->where('city', 'like', '%' . $term . '%')
            ->groupBy('city')
            ->take(10)
            ->get();
        return response()->json($cities);



    }


}
