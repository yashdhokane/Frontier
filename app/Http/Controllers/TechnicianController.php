<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
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
        // Validate the request
        $validator = Validator::make($request->all(), [

            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_phone' => 'required|max:20',
            // 'home_phone' => 'required|max:20',
            // 'work_phone' => 'required|max:20',
//    'role' => 'required',

            'address1' => 'required',
            // 'address_unit' => 'required',

            'city' => 'required',
            'state_id' => 'required',
            // 'tag_id' => 'required',
            'zip_code' => 'max:10',
            'license_number' => 'required',


             'dob' => 'required', // Update the maximum length as needed

            // 'image' => 'required',


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
        // Check if service areas are selected before imploding
        $user->service_areas = !empty($request['service_areas']) ? implode(',', $request['service_areas']) : null;


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/technician/'), $imageName);
            $user->user_image = $imageName;
        }
        $user->save();

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

        $customerAddress = new CustomerUserAddress();
        $customerAddress->user_id = $userId;
        $customerAddress->address_line1 = $request['address1'];
        $customerAddress->address_line2 = $request['address_unit'];
        $customerAddress->city = $request['city'];
        $customerAddress->state_id = $request['state_id'];
        $customerAddress->zipcode = $request['zip_code'];
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



        // dd("end");
        return redirect()->route('technicians.index')->with('success', 'Technician created successfully');
    }



    public function show($id)
    {

        $technician = User::find($id);
        $meta = $technician->meta;
        $home_phone = $technician->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
        $location = CustomerUserAddress::where('user_id', $technician->id)->get();

        $jobasigndate = DB::table('job_assigned')
            ->where('technician_id', $technician->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $jobasign = DB::table('job_assigned')
            ->where('technician_id', $technician->id)
            ->get();
        $customerimage = DB::table('user_files')
            ->where('user_id', $technician->id)
            ->get();

        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $technician->id)
            ->value('location_cities.city');

        $latitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $technician->id)
            ->value('location_cities.latitude');
        $longitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $technician->id)
            ->value('location_cities.longitude');

        return view('technicians.show', compact('technician', 'longitude', 'latitude', 'userAddresscity', 'jobasign', 'customerimage', 'location', 'jobasigndate', 'home_phone'));
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

            return view('technicians.edit', compact('technician', 'serviceAreas','license_number', 'ssn','dob','locationStates', 'userTags', 'selectedTags', 'meta', 'location', 'Note', 'tags', 'source', 'first_name', 'last_name', 'home_phone', 'work_phone', 'cities'));
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
        $user->service_areas = $request['service_areas'];
        if ($request->filled('password')) {
            $user->password = Hash::make($request['password']);
        }
        if ($request->hasFile('image')) {
            // Handle image update logic here
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/technician/'), $imageName);

            // Remove the old image if it exists
            if ($user->user_image) {
                $oldImagePath = public_path('images/technician/') . '/' . $user->user_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $user->user_image = $imageName;
        }
        if ($request->has('service_areas') && is_array($request->input('service_areas'))) {
            // If service areas are provided, implode the array
            $serviceAreasString = implode(',', $request->input('service_areas'));
            $user->service_areas = $serviceAreasString;
        } elseif (!$user->service_areas) {

            $user->service_areas = '';
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
        $user->location()->updateOrCreate(
            ['user_id' => $id],
            [
                'address_line1' => $request['address1'],
                'address_line2' => $request['address_unit'],
                'city' => $request['city'],
                'state_id' => $request['state_id'],
                'zipcode' => $request['zip_code'],
            ]
        );

        // Update user notes
        $user->Note()->updateOrCreate(
            ['user_id' => $id],
            ['note' => $request['customer_notes']]
        );

        // Update user tags
        //$tagIds = implode(',', $request['tag_id']);
        //  $user->tags()->sync(explode(',', $tagIds));
        // Get the tag IDs from the request
        $tagIds = $request->input('tag_id', []);

        // Attach all new tags to the user
        $user->tags()->attach($tagIds);

        // Synchronize existing tags without detaching
        $user->tags()->syncWithoutDetaching($tagIds);


        return redirect()->route('technicians.index')->with('success', 'Technician updated successfully');
    }



    public function technicianGet()
    {
        $technicians = Technician::get();
        return view('technicians.technician_loactor', compact('technicians'));
    }


}