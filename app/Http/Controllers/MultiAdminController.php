<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;


use App\Models\User;
use App\Models\UserTag;
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
        $users = User::where('role', 'admin')->orderBy('created_at', 'desc') // Assuming 'created_at' is a timestamp column
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
        $customerAddress->address_primary = ($request['address_type'] == 'home') ? 'yes' : 'no';
        $customerAddress->address_type = $request['address_type'];
        $customerAddress->city = $request['city'];
        $customerAddress->state_id = $request['state_id'];
        $customerAddress->zipcode = $request['zip_code'];
        $customerAddress->save();

        $tagIds = $request['tag_id'];

        if (!empty ($tagIds)) {
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
        

        $notename = DB::table('user_notes')->where('user_id',
                                        $multiadmin->id)->get();
        $meta = $multiadmin->meta;
        $jobasign = DB::table('jobs')
            ->where('added_by', $multiadmin->id)
            ->get();
        $activity = DB::table('job_activity')
            ->where('user_id', $multiadmin->id)
            ->get();
        $home_phone = $multiadmin->meta()->where('meta_key', 'home_phone')->value('meta_value') ?? '';
        $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $multiadmin->id)
            ->value('location_cities.city');

        $latitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $multiadmin->id)
            ->value('location_cities.latitude');
        $longitude = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
            ->where('user_address.user_id', $multiadmin->id)
            ->value('location_cities.longitude');
        $location = CustomerUserAddress::where('user_id', $multiadmin->id)->get();


        return view('multiadmin.show', compact('multiadmin', 'notename','activity', 'jobasign', 'longitude', 'latitude', 'userAddresscity', 'location', 'home_phone'));
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
                'address_type' => $request['address_type'],

                'address_primary' => ($request['address_type'] == 'home') ? 'yes' : 'no',
            ]
        );

        // Update user notes

        $tagIds = $request->input('tag_id', []);
        $user->tags()->detach();
        $user->tags()->attach($tagIds);
        $user->tags()->syncWithoutDetaching($tagIds);


        return redirect()->route('multiadmin.index')->with('success', 'Admin updated successfully');
    }



    // public function technicianGet()
    // {
    //     $technicians = Technician::get();
    //     return view('technicians.technician_loactor', compact('technicians'));
    // }


}
