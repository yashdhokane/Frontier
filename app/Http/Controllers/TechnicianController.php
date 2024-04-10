<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\FleetDetails;
use App\Models\LocationCity;
use App\Models\UserTag;
use App\Models\SiteTags;
use App\Models\Technician;
use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\CustomerUserMeta;
use App\Models\UserNotesCustomer;
use App\Models\UserTagIdCategory;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerUserAddress;
use App\Models\LocationServiceArea;
use App\Models\UserTagsTechnicians;
use Illuminate\Support\Facades\File;
use App\Models\UserLeadSourceCustomer;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{

    public function index()
    {


        $users = User::where('role', 'technician')->orderBy('created_at', 'desc')->get();

        foreach ($users as $key => $value) {
            $areaName = [];
            if (isset($value->service_areas) && !empty($value->service_areas)) {
                $service_areas = explode(',', $value->service_areas);
                foreach ($service_areas as $key1 => $value1) {
                    $location_service_area = DB::table('location_service_area')->where('area_id', $value1)->first();
                    if (isset($location_service_area->area_name) && !empty($location_service_area->area_name)) {
                        $areaName[] = $location_service_area->area_name;
                    }
                }
                $users[$key]['area_name'] = implode(', ', $areaName);
            }
        }

        return view('technicians.index', compact('users'));
    }


    public function create()
    {

        $serviceAreas = LocationServiceArea::all();
        $users = User::all();
        //    $roles = Role::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all(); // Fetch all tags


        return view('technicians.create', compact('users', 'serviceAreas', 'tags', 'locationStates'));
    }

    public function store(Request $request)
    {
        //dd($request->all());

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
            'license_number' => 'required',
            'dob' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If validation passes, create the user and related records
        $user = new User();
        $user->name = $request['display_name'];
        $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        $user->role = $request['role'];
        $user->password = Hash::make($request['password']);
        // $user->service_areas = implode(',', $request['service_areas']);
        $user->service_areas = !empty($request['service_areas']) ? implode(',', $request['service_areas']) : null;

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
        $currentTimestamp = now();

        $userMeta = [
            ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $request['first_name']],
            ['user_id' => $userId, 'meta_key' => 'last_name', 'meta_value' => $request['last_name']],
            ['user_id' => $userId, 'meta_key' => 'home_phone', 'meta_value' => $request['home_phone']],
            ['user_id' => $userId, 'meta_key' => 'work_phone', 'meta_value' => $request['work_phone']],
            ['user_id' => $userId, 'meta_key' => 'license_number', 'meta_value' => $request['license_number']],
            ['user_id' => $userId, 'meta_key' => 'dob', 'meta_value' => $request['dob']],
            ['user_id' => $userId, 'meta_key' => 'ssn', 'meta_value' => $request['ssn']],
            ['user_id' => $userId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
            ['user_id' => $userId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        ];
        CustomerUserMeta::insert($userMeta);


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



        // dd("end");
        return redirect()->route('technicians.index')->with('success', 'Technician created successfully');
    }
    public function fleettechnician(Request $request)
    {
        //dd($request->all());

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
            'license_number' => 'required',
            'dob' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If validation passes, create the user and related records
        $user = new User();
        $user->name = $request['display_name'];
        $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        $user->role = $request['role'];
        $user->password = Hash::make($request['password']);
        // $user->service_areas = implode(',', $request['service_areas']);
        $user->service_areas = !empty($request['service_areas']) ? implode(',', $request['service_areas']) : null;

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
        $currentTimestamp = now();

        $userMeta = [
            ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $request['first_name']],
            ['user_id' => $userId, 'meta_key' => 'last_name', 'meta_value' => $request['last_name']],
            ['user_id' => $userId, 'meta_key' => 'home_phone', 'meta_value' => $request['home_phone']],
            ['user_id' => $userId, 'meta_key' => 'work_phone', 'meta_value' => $request['work_phone']],
            ['user_id' => $userId, 'meta_key' => 'license_number', 'meta_value' => $request['license_number']],
            ['user_id' => $userId, 'meta_key' => 'dob', 'meta_value' => $request['dob']],
            ['user_id' => $userId, 'meta_key' => 'ssn', 'meta_value' => $request['ssn']],
            ['user_id' => $userId, 'meta_key' => 'created_at', 'meta_value' => $currentTimestamp],
            ['user_id' => $userId, 'meta_key' => 'updated_at', 'meta_value' => $currentTimestamp],
        ];
        CustomerUserMeta::insert($userMeta);


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



        // dd("end");
        return redirect()->back()->with('success', 'Technician created successfully');
    }



    public function show($id)
    {



        $technician = User::find($id);
        //dd($technician);


        $notename = DB::table('user_notes')->where(
            'user_id',
            $technician->id
        )->get();
        $meta = $technician->meta;
        $home_phone = $technician->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
        $location = CustomerUserAddress::where('user_id', $technician->id)->get();

        $jobasigndate = DB::table('job_assigned')
            ->where('technician_id', $technician->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $jobasign = DB::table('jobs')
            ->where('technician_id', $technician->id)
            ->get();




        //  $payments = DB::table('payments')
        // ->where('job_id', $technician->id)
        // ->get();
        $payments = DB::table('payments')
            ->whereIn('job_id', function ($query) use ($technician) {
                $query->select('id')
                    ->from('jobs')
                    ->where('technician_id', $technician->id);
            })
            ->get();

        //dd( $payments);




        $customerimage = DB::table('user_files')
            ->where('user_id', $technician->id)
            ->get();

        // $userAddresscity = DB::table('user_address')
        //     ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
        //     ->where('user_address.user_id', $technician->id)
        //     ->value('location_cities.city');
        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
            ->where('user_address.user_id', $technician->id)
            ->get();
        $latitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $technician->id)
            ->value('location_cities.latitude');
        $longitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $technician->id)
            ->value('location_cities.longitude');
        $fleets = $technician->fleet;
        if ($fleets) {
            $oil_change = $technician->fleet()->where('fleet_key', 'oil_change')->value('fleet_value') ?? '';
            $tune_up = $technician->fleet()->where('fleet_key', 'tune_up')->value('fleet_value') ?? '';
            $tire_rotation = $technician->fleet()->where('fleet_key', 'tire_rotation')->value('fleet_value') ?? '';
            $breaks = $technician->fleet()->where('fleet_key', 'breaks')->value('fleet_value') ?? '';
            $inspection_codes = $technician->fleet()->where('fleet_key', 'inspection_codes')->value('fleet_value') ?? '';
            $mileage = $technician->fleet()->where('fleet_key', 'mileage')->value('fleet_value') ?? '';
            $registration_expiration_date = $technician->fleet()->where('fleet_key', 'registration_expiration_date')->value('fleet_value') ?? '';
            $vehicle_coverage = $technician->fleet()->where('fleet_key', 'vehicle_coverage')->value('fleet_value') ?? '';
            $license_plate = $technician->fleet()->where('fleet_key', 'license_plate')->value('fleet_value') ?? '';
            $vin_number = $technician->fleet()->where('fleet_key', 'vin_number')->value('fleet_value') ?? '';
            $make = $technician->fleet()->where('fleet_key', 'make')->value('fleet_value') ?? '';
            $model = $technician->fleet()->where('fleet_key', 'model')->value('fleet_value') ?? '';
            $year = $technician->fleet()->where('fleet_key', 'year')->value('fleet_value') ?? '';
            $color = $technician->fleet()->where('fleet_key', 'color')->value('fleet_value') ?? '';
            $vehicle_weight = $technician->fleet()->where('fleet_key', 'vehicle_weight')->value('fleet_value') ?? '';
            $vehicle_cost = $technician->fleet()->where('fleet_key', 'vehicle_cost')->value('fleet_value') ?? '';
            $use_of_vehicle = $technician->fleet()->where('fleet_key', 'use_of_vehicle')->value('fleet_value') ?? '';
            $repair_services = $technician->fleet()->where('fleet_key', 'repair_services')->value('fleet_value') ?? '';
            $ezpass = $technician->fleet()->where('fleet_key', 'ezpass')->value('fleet_value') ?? '';
            $service = $technician->fleet()->where('fleet_key', 'service')->value('fleet_value') ?? '';
            $additional_service_notes = $technician->fleet()->where('fleet_key', 'additional_service_notes')->value('fleet_value') ?? '';
            $last_updated = $technician->fleet()->where('fleet_key', 'last_updated')->value('fleet_value') ?? '';
            $epa_certification = $technician->fleet()->where('fleet_key', 'epa_certification')->value('fleet_value') ?? '';
        } else {
            $oil_change = '';
            $tune_up = '';
            $tire_rotation = '';
            $breaks = '';
            $inspection_codes = '';
            $mileage = '';
            $registration_expiration_date = '';
            $vehicle_coverage = '';
            $license_plate = '';
            $vin_number = '';
            $make = '';
            $model = '';
            $year = '';
            $color = '';
            $vehicle_weight = '';
            $vehicle_cost = '';
            $use_of_vehicle = '';
            $repair_services = '';
            $ezpass = '';
            $service = '';
            $additional_service_notes = '';
            $last_updated = '';
            $epa_certification = '';
        }

        $meta = $technician->meta;
        if ($meta) {
            $first_name_e = $technician->meta()->where('meta_key', 'first_name')->value('meta_value') ?? '';
            $last_name_e = $technician->meta()->where('meta_key', 'last_name')->value('meta_value') ?? '';
            $home_phone_e = $technician->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
            $work_phone_e = $technician->meta()->where('meta_key', 'work_phone')->value('meta_value') ?? '';
            $ssn_e = $technician->meta()->where('meta_key', 'ssn')->value('meta_value') ?? '';
            $dob_e = $technician->meta()->where('meta_key', 'dob')->value('meta_value') ?? '';
            $license_number_e = $technician->meta()->where('meta_key', 'license_number')->value('meta_value') ?? '';
        }
        $serviceAreas = LocationServiceArea::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all();



        // Assuming you have a 'tags' relationship defined in your User model
        $userTags = $technician->tags;

        // Convert the comma-separated tag_id string to an array
        $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));
        $location = $technician->location;


        // Check if the technician has a location
        if ($location) {
            // Fetch cities associated with the technician's state
            $cities = LocationCity::where('state_id', $location->state_id)->get();
        } else {
            $cities = collect(); // No cities if no location is set
        }

        $Note = $technician->Note;
        $source = $technician->source;



        return view('technicians.show', compact('technician', 'oil_change', 'tune_up', 'tire_rotation', 'breaks', 'inspection_codes', 'mileage', 'registration_expiration_date', 'vehicle_coverage', 'license_plate', 'vin_number', 'make', 'model', 'year', 'color', 'vehicle_weight', 'vehicle_cost', 'use_of_vehicle', 'repair_services', 'ezpass', 'service', 'additional_service_notes', 'last_updated', 'epa_certification', 'notename', 'payments', 'longitude', 'latitude', 'userAddresscity', 'jobasign', 'customerimage', 'location', 'jobasigndate', 'home_phone', 'first_name_e', 'last_name_e', 'home_phone_e', 'work_phone_e', 'license_number_e', 'ssn_e', 'dob_e', 'serviceAreas', 'locationStates', 'tags', 'cities', 'selectedTags', 'userTags'));
    }

    public function edit($id)
    {
        $technician = User::find($id);
        $locationStates = LocationState::all();
        $serviceAreas = LocationServiceArea::all();
        // Check if $user is found
        if (!$technician) {
            // Handle the case where the user is not found, perhaps redirect to an error page
            return redirect()->route('technicians.index');
        }

        // Check if $user has meta relationship
        $meta = $technician->meta;
        if ($meta) {
            $first_name = $technician->meta()->where('meta_key', 'first_name')->value('meta_value') ?? '';
            $last_name = $technician->meta()->where('meta_key', 'last_name')->value('meta_value') ?? '';
            $home_phone = $technician->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
            $work_phone = $technician->meta()->where('meta_key', 'work_phone')->value('meta_value') ?? '';
            $ssn = $technician->meta()->where('meta_key', 'ssn')->value('meta_value') ?? '';
            $dob = $technician->meta()->where('meta_key', 'dob')->value('meta_value') ?? '';
            $license_number = $technician->meta()->where('meta_key', 'license_number')->value('meta_value') ?? '';

            $location = $technician->location;


            // Check if the technician has a location
            if ($location) {
                // Fetch cities associated with the technician's state
                $cities = LocationCity::where('state_id', $location->state_id)->get();
            } else {
                $cities = collect(); // No cities if no location is set
            }

            $Note = $technician->Note;
            $source = $technician->source;

            $tags = SiteTags::all();



            // Assuming you have a 'tags' relationship defined in your User model
            $userTags = $technician->tags;

            // Convert the comma-separated tag_id string to an array
            $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));


            // $serviceAreas = $technician->serviceAreas()->get();

            return view('technicians.edit', compact('technician', 'serviceAreas', 'license_number', 'ssn', 'dob', 'locationStates', 'userTags', 'selectedTags', 'meta', 'location', 'Note', 'tags', 'source', 'first_name', 'last_name', 'home_phone', 'work_phone', 'cities'));
        } else {
            // Handle the case where meta is not found, perhaps redirect to an error page
            return redirect()->route('technicians.index');
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $id,
            'mobile_phone' => 'required|max:20',
            'address1' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'license_number' => 'required',

            'zip_code' => 'max:10',
            'dob' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('technicians.index')->with('error', 'Technician not found');
        }

        $user->name = $request['display_name'];
        // $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        $user->role = $request['role'];
        // $user->service_areas = $request['service_areas'];
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

        // if ($request->has('service_areas') && is_array($request->input('service_areas'))) {
        // If service areas are provided, implode the array
        //    $serviceAreasString = implode(',', $request->input('service_areas'));
        //  $user->service_areas = $serviceAreasString;
        //  } elseif (!$user->service_areas) {

        // $user->service_areas = '';
        // }

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
        $user->meta()->updateOrCreate(
            ['meta_key' => 'dob'],
            ['meta_value' => $request['dob']]
        );
        $user->meta()->updateOrCreate(
            ['meta_key' => 'ssn'],
            ['meta_value' => $request['ssn']]
        );
        $user->meta()->updateOrCreate(
            ['meta_key' => 'license_number'],
            ['meta_value' => $request['license_number']]
        );
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




        return redirect()->back();
    }

    public function updateservice(Request $request, $id)
    {
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('technicians.index')->with('error', 'Technician not found');
        }



        if ($request->has('service_areas') && is_array($request->input('service_areas'))) {
            // If service areas are provided, implode the array
            $serviceAreasString = implode(',', $request->input('service_areas'));
            $user->service_areas = $serviceAreasString;
        } elseif (!$user->service_areas) {

            $user->service_areas = '';
        }

        $user->save();


        return redirect()->back();
    }



    public function technicianGet()
    {
        $technicians = Technician::get();
        return view('technicians.technician_loactor', compact('technicians'));
    }


    public function techniciancomment(Request $request)
    {
        $addedByUserId = auth()->user()->id;
        $user = User::findOrFail($request->id);

        $payment = new UserNotesCustomer();
        $payment->user_id = $user->id;
        $payment->added_by = $addedByUserId;
        $payment->last_updated_by = $addedByUserId;

        $payment->note = $request->note;

        $payment->save();

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function technicianstaus(Request $request)
    {
        //dd($id);

        // Find the user based on the provided $id
        $user = User::findOrFail($request->user_id);
        //dd($user);

        // Check if the current status is 'active', then change it to 'deactive'
        if ($user->status == 'active') {
            $user->status = 'deactive';
        } else {
            // Otherwise, change it to 'active'
            $user->status = 'active';
        }

        // Check if the current login state is 'enable', then change it to 'disable'
        if ($user->login == 'enable') {
            $user->login = 'disable';
        } else {
            // Otherwise, change it to 'enable'
            $user->login = 'enable';
        }
        // dd($user->login,$user->status)
        // Save the changes to the user model
        $user->save();

        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function updatefleet(Request $request)
    {
        //dd($request->all());
        $user = User::find($request->id);


        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'oil_change'],
            ['fleet_value' => $request->input('oil_change')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'tune_up'],
            ['fleet_value' => $request->input('tune_up')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'tire_rotation'],
            ['fleet_value' => $request->input('tire_rotation')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'breaks'],
            ['fleet_value' => $request->input('breaks')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'inspection_codes'],
            ['fleet_value' => $request->input('inspection_codes')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'mileage'],
            ['fleet_value' => $request->input('mileage')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'registration_expiration_date'],
            ['fleet_value' => $request->input('registration_expiration_date')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'vehicle_coverage'],
            ['fleet_value' => $request->input('vehicle_coverage')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'license_plate'],
            ['fleet_value' => $request->input('license_plate')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'vin_number'],
            ['fleet_value' => $request->input('vin_number')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'make'],
            ['fleet_value' => $request->input('make')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'model'],
            ['fleet_value' => $request->input('model')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'year'],
            ['fleet_value' => $request->input('year')]
        );


        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'color'],
            ['fleet_value' => $request->input('color')]
        );


        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'vehicle_weight'],
            ['fleet_value' => $request->input('vehicle_weight')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'vehicle_cost'],
            ['fleet_value' => $request->input('vehicle_cost')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'use_of_vehicle'],
            ['fleet_value' => $request->input('use_of_vehicle')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'repair_services'],
            ['fleet_value' => $request->input('repair_services')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'ezpass'],
            ['fleet_value' => $request->input('ezpass')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'service'],
            ['fleet_value' => $request->input('service')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'additional_service_notes'],
            ['fleet_value' => $request->input('additional_service_notes')]
        );
        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'last_updated'],
            ['fleet_value' => $request->input('last_updated')]
        );

        $user->fleet()->updateOrCreate(
            ['fleet_key' => 'epa_certification'],
            ['fleet_value' => $request->input('epa_certification')]
        );



        return redirect()->back()->with('success', 'Fleet data updated successfully!');
    }
    // public function updatefleet(Request $request)
    // {

    //     // Find the FleetDetails record based on the user ID
    // $fleetDetails = FleetDetails::where('user_id', $request->id)->first();
    //  dd($fleetDetails);
    //     // Update or create each field in the fleet details
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'oil_change'], ['fleet_value' => $request->input('oil_change')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'tune_up'], ['fleet_value' => $request->input('tune_up')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'tire_rotation'], ['fleet_value' => $request->input('tire_rotation')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'breaks'], ['fleet_value' => $request->input('breaks')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'inspection_codes'], ['fleet_value' => $request->input('inspection_codes')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'mileage'], ['fleet_value' => $request->input('mileage')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'registration_expiration_date'], ['fleet_value' => $request->input('registration_expiration_date')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'vehicle_coverage'], ['fleet_value' => $request->input('vehicle_coverage')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'license_plate'], ['fleet_value' => $request->input('license_plate')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'vin_number'], ['fleet_value' => $request->input('vin_number')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'make'], ['fleet_value' => $request->input('make')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'model'], ['fleet_value' => $request->input('model')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'year'], ['fleet_value' => $request->input('year')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'color'], ['fleet_value' => $request->input('color')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'vehicle_weight'], ['fleet_value' => $request->input('vehicle_weight')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'vehicle_cost'], ['fleet_value' => $request->input('vehicle_cost')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'use_of_vehicle'], ['fleet_value' => $request->input('use_of_vehicle')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'repair_services'], ['fleet_value' => $request->input('repair_services')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'ezpass'], ['fleet_value' => $request->input('ezpass')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'service'], ['fleet_value' => $request->input('service')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'additional_service_notes'], ['fleet_value' => $request->input('additional_service_notes')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'last_updated'], ['fleet_value' => $request->input('last_updated')]);
    //     $fleetDetails->updateOrCreate(['fleet_key' => 'epa_certification'], ['fleet_value' => $request->input('epa_certification')]);

    //     return redirect()->back()->with('success', 'Fleet data updated successfully!');
    // }




}
