<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\CustomerUserAddress;
use Illuminate\Support\Facades\Hash;

// use Google\Service\AnalyticsReporting\User;

class AdminProfileController extends Controller
{
    public function index()
    {
        $userAddress = CustomerUserAddress::first();
        $user = User::first();
        $locationStates = LocationState::all();
        return view('adminprofile.myprofile', compact('locationStates', 'user', 'userAddress'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'image' => 'required|mimes:gif,jpg,png,jpeg',
            // 'content' => 'required',
        ]);

        $filename = '';
        if ($request->hasFile('user_image')) {
            $file = $request->file('user_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/'), $filename);
        }


        User::where('id', $request->id)->update(
            [
                'user_image' => $filename,

            ]
        );

        return redirect()->back()->with('success', 'Image Updated successfully.');
    }


    public function passstore(Request $request)
    {
        $request->validate([
            // 'currentpassword' => 'required',
            // 'password' => 'required|min:8', // Adjust the minimum length as needed
            // 'conformpassword' => 'required|same:newpassword',
        ]);

        $user = User::find($request->id);

        // Check if the current password matches the one in the database
        if (!Hash::check($request->currentpassword, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update the user's password
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }


    public function infoadmin(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // 'currentpassword' => 'required',
            // 'password' => 'required|min:8', // Adjust the minimum length as needed
            // 'conformpassword' => 'required|same:newpassword',
        ]);

        User::where('id', $request->id)->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,

            ]
        );

        CustomerUserAddress::where('user_id', $request->id)->update(
            [
                'city' => $request->city,
                'state_id' => $request->state_id,
                'address_line1' => $request->address_line1,

            ]
        );
        // dd(1);

        return redirect()->back()->with('success', 'Information updated successfully.');
    }

}
