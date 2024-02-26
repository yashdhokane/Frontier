<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use App\Models\User;
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
        $users = User::all();
        //    $roles = Role::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all(); // Fetch all tags


        return view('dispatcher.create', compact('users', 'tags', 'locationStates'));
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
            'zip_code' => 'max:10',
          //  'tag_id' => 'required',


            // 'user_tags' => 'required', // Update the maximum length as needed

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


if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = time() . '_' . $image->getClientOriginalName();

    // Determine the user's image folder path
    $userFolder = public_path('images/dispatcher/') . '/' . $user->id;

    // Create the user's image folder if it doesn't exist
    if (!file_exists($userFolder)) {
        mkdir($userFolder, 0777, true);
    }

    // Move the image to the user's folder
    $image->move($userFolder, $imageName);

    // Update the user's image field
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




        //    dd("end");
        return redirect()->route('dispatcher.index')->with('success', 'Dispatcher created successfully');
    }



    public function show($id)
    {
         $dispatcher = User::find($id);
          $meta = $dispatcher->meta;
        $home_phone = $dispatcher->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
 $userAddresscity = DB::table('user_address')
    ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
    ->where('user_address.user_id', $dispatcher->id)
    ->value('location_cities.city');

     $latitude = DB::table('user_address')
    ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
    ->where('user_address.user_id', $dispatcher->id)
    ->value('location_cities.latitude');
     $longitude = DB::table('user_address')
    ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
    ->where('user_address.user_id', $dispatcher->id)
    ->value('location_cities.longitude');

$location = CustomerUserAddress::where('user_id', $dispatcher->id)->get();

       
       

        return view('dispatcher.show', compact('dispatcher','location','latitude','longitude','userAddresscity', 'home_phone'));
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



            // Assuming you have a 'tags' relationship defined in your User model
            $userTags = $dispatcher->tags;

            // Convert the comma-separated tag_id string to an array
            $selectedTags = explode(',', $userTags->pluck('tag_id')->implode(','));

            return view('dispatcher.edit', compact('dispatcher', 'locationStates', 'userTags', 'selectedTags', 'meta', 'location', 'Note', 'tags', 'source', 'first_name', 'last_name', 'home_phone', 'work_phone'));
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
            // Handle image update logic here
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/dispatcher'), $imageName);

            // Remove the old image if it exists
            if ($user->user_image) {
                $oldImagePath = public_path('images/dispatcher') . '/' . $user->user_image;
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
      $tagIds = $request->input('tag_id', []);

        // Attach all new tags to the user
        $user->tags()->attach($tagIds);

        // Synchronize existing tags without detaching
        $user->tags()->syncWithoutDetaching($tagIds);

        return redirect()->route('dispatcher.index')->with('success', 'Dispatcher updated successfully');
    }



    // public function technicianGet()
    // {
    //     $technicians = Technician::get();
    //     return view('technicians.technician_loactor', compact('technicians'));
    // }


}
