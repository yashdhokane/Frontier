<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\LocationCity;
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





class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    
    $users = User::where('role', 'customer')
                  ->orderBy('created_at', 'desc') // Assuming 'created_at' is a timestamp column
                  ->get();

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
$cities = LocationCity::all(); 
$cities1 = LocationCity::all(); 


    return view('users.create', compact('users', 'roles', 'locationStates', 'locationStates1' ,'leadSources', 'tags', 'cities','cities1'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      // dd($request->all());
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
        $user->company = $request['company'];
        $user->user_type = $request['user_type'];
        $user->position = $request['role'];
        $user->source_id = $request['source_id'];
         $user->password = Hash::make($request['password']);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/customer'), $imageName);
            $user->user_image = $imageName;
        }
        $user->save();

        $userId = $user->id;
        $currentTimestamp = now();

         $user->meta()->createMany( [
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
if ($request->filled('anotheraddress_type') && $request->filled('anothercity')) {
    $customerAddress = new CustomerUserAddress();
    $customerAddress->user_id = $userId;
    $customerAddress->address_line1 = $request['anotheraddress1'];
    $customerAddress->address_line2 = $request['anotheraddress_unit'];
    $customerAddress->city = $request['anothercity'];
    $customerAddress->address_type = $request['anotheraddress_type'];
    $customerAddress->state_id = $request['anotherstate_id'];
    $customerAddress->zipcode = $request['anotherzip_code'];
    $customerAddress->save();
}


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




        // dd("end");
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }



     public function show($id)
    {
        $roles = Role::all();
        $user = User::find($id);

        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
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


        $jobasign = DB::table('job_assigned')
            ->where('customer_id', $user->id)
            ->get();



        $customerimage = DB::table('user_files')
            ->where('user_id', $user->id)
            ->get();
        // dd($jobasign);


        return view('users.show', compact('user', 'userAddresscity', 'jobasigndate', 'customerimage', 'jobasign', 'roles', 'home_phone', 'location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
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

        return view('users.edit', compact(
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
        ));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    //    dd($request->all());
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
       // $user->email = $request['email'];
        $user->mobile = $request['mobile_phone'];
        $user->company = $request['company'];
        $user->user_type = $request['user_type'];
        $user->position = $request['role'];
        $user->source_id = $request['source_id'];
        if ($request->filled('password')) {
    $user->password = Hash::make($request['password']);
}

        if ($request->hasFile('image')) {
            // Handle image update logic here
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/customer'), $imageName);

            // Remove the old image if it exists
            if ($user->user_image) {
                $oldImagePath = public_path('images/customer') . '/' . $user->user_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
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
$user->location()
     ->where('user_id', $id)
    ->delete();
// Update the location records including the first one
// Update or create the first location record if the required fields are not null
if ($request['address1'] && $request['address_unit'] && $request['city'] && $request['zip_code'] && $request['state_id'] && $request['zip_code'] && $request['address_type']) {
    $user->location()
    ->take(1)
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

// Update or create the second location record if the required fields are not null
if ($request['anotheraddress1'] && $request['anotheraddress_unit'] && $request['anothercity'] && $request['anotherzip_code'] && $request['anotherstate_id'] && $request['anotherzip_code'] && $request['anotheraddress_type']) {
    $user->location()
        ->skip(1) 
        ->take(1)// Skip the first record
        ->updateOrCreate(
            ['user_id' => $id, 'address_type' => $request['anotheraddress_type']],
            [
                'address_line1' => $request['anotheraddress1'],
                'address_line2' => $request['anotheraddress_unit'],
                'city' => $request['anothercity'],
                'state_id' => $request['anotherstate_id'],
                'zipcode' => $request['anotherzip_code'],
                'address_type' => $request['anotheraddress_type'],
            ]
        );
}

    //     $user->location()->updateOrCreate(
    // ['user_id' => $id, 'address_type' => 'other'],
    //         [
    //             'address_line1' => $request['anothertwoaddress1'],
    //             'address_line2' => $request['anothertwoaddress_unit'],
    //             'city' => $request['anothertwocity'],
    //             'state_id' => $request['anothertwostate_id'],
    //             'zipcode' => $request['anothertwozip_code'],
    //             'address_type' => $request['anothertwoaddress_type'],
    //         ]
    //     );


        // Update user notes
        $user->Note()->updateOrCreate(
            ['user_id' => $id],
            ['note' => $request['customer_notes']]
        );

        // Update user tags
  $tagIds = $request->input('tag_id', []);

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


public function updatePassword(Request $request) {
    $request->validate([
        'id' => 'required|exists:users,id',
        'password' => 'required|',
    ]);

    $user = User::findOrFail($request->id);
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->back()->with('success', 'Password updated successfully.');
}




}