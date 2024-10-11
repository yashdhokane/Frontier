<?php

namespace App\Models;

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\BuisnessProfileModel;



use Illuminate\Http\Request;

use Carbon\Carbon;



class BuisnessProfileController extends Controller

{
    public function index()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 53;

        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $businessProfiles = BuisnessProfileModel::get();

        return view('buisnessprofile.buisnessprofile', compact('businessProfiles'));
    }


    public function update(Request $request)
    {
        // Find the model by ID
        $businessProfile = BuisnessProfileModel::find($request->id);

        if ($businessProfile) {
            // Update model properties without validation
            $businessProfile->business_name = $request->businessName;
            $businessProfile->address = $request->address;
            $businessProfile->email = $request->supportEmail;
            $businessProfile->license_number = $request->licenseNumber;
            $businessProfile->phone = $request->phone; // Assuming 'businessPhone' is the correct field name
            $businessProfile->website = $request->website;
            $businessProfile->legal_name = $request->legal_name;
            $businessProfile->hvac = $request->heatingAndAirConditioning;

            // Save the changes
            $businessProfile->save();
            // dd(1);
            // Add success flash message
            return redirect()->back()->with('success', 'The business profile has been updated successfully.');
        } else {
            // Handle the case where the model with the given ID is not found
            return redirect()->back()->with('error', 'Business profile not found.');
        }
    }



    public function bpupdate(Request $request)
    {
        // Find the model by ID
        $businessProfile = BuisnessProfileModel::find($request->id);

        if ($businessProfile) {
            // Update model properties without validation
            $businessProfile->description_long = $request->description_long;
            $businessProfile->save();
            // dd(1);
            // Add success flash message
            return redirect()->back()->with('success', 'The business profile has been updated successfully.');
        } else {
            // Handle the case where the model with the given ID is not found
            return redirect()->back()->with('error', 'Business profile not found.');
        }
    }



    public function moiupdate(Request $request)
    {
        // Find the model by ID
        $businessProfile = BuisnessProfileModel::find($request->id);

        if ($businessProfile) {
            // Update model properties without validation
            $businessProfile->message_on_docs = $request->message_on_docs;
            $businessProfile->save();
            // dd(1);
            // Add success flash message
            return redirect()->back()->with('success', 'The business profile has been updated successfully.');
        } else {
            // Handle the case where the model with the given ID is not found
            return redirect()->back()->with('error', 'Business profile not found.');
        }
    }


    public function tacupdate(Request $request)
    {
        // Find the model by ID
        $businessProfile = BuisnessProfileModel::find($request->id);

        if ($businessProfile) {
            // Update model properties without validation
            $businessProfile->terms_condition = $request->terms_condition;
            $businessProfile->save();
            // dd(1);
            // Add success flash message
            return redirect()->back()->with('success', 'The business profile has been updated successfully.');
        } else {
            // Handle the case where the model with the given ID is not found
            return redirect()->back()->with('error', 'Business profile not found.');
        }
    }
}
