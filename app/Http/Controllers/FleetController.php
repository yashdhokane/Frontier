<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\SiteTags;
use App\Models\FleetDetails;
use App\Models\FleetVehicle;
use Illuminate\Http\Request;
use App\Models\LocationState;
use App\Models\LocationServiceArea;
use Illuminate\Support\Facades\Auth;
use App\Models\VehicleInsurancePolicy;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 50;
        $technician = User::where('role', 'technician')->where('status', 'active')->get();
        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $vehicle = FleetVehicle::orderBy('created_at', 'desc')->get();

        return view('fleet.index', compact('vehicle', 'technician'));
    }

    public function inactive(Request $request, $id)
    {

        $product = FleetVehicle::find($id);

        $product->status = 'inactive';

        $product->update();

        return redirect()->back()->with('success', 'Status Inactive successfully');
    }

    public function active(Request $request, $id)
    {

        $product = FleetVehicle::find($id);

        $product->status = 'active';

        $product->update();

        return redirect()->back()->with('success', 'Status Active successfully');
    }

    public function addvehicle(Request $request)
    {
        $user = User::where('role', 'technician')->where('status', 'active')->get();
        $serviceAreas = LocationServiceArea::all();
        $users = User::all();
        //    $roles = Role::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all(); // Fetch all tags

        return view('fleet.create', compact('user', 'users', 'serviceAreas', 'tags', 'locationStates'));
    }

    public function store(Request $request)
    { // dd($request->all());

        if ($request->hasFile('vehicle_image')) {
            $categoryImage = $request->file('vehicle_image');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('vehicle_image'), $imageName);
        } else {
            $imageName = null;
        }
        $vehicle = new FleetVehicle();
        $vehicle->vehicle_image = $imageName;
        $vehicle->vehicle_no = $request->vehicle_no;
        $vehicle->vehicle_name = $request->vehicle_name;


        // $vehicle->vehicle_summary = $request->vehicle_summary;
        $vehicle->vehicle_description = $request->vehicle_description;
        $vehicle->technician_id = $request->technician_id;
        $vehicle->created_by = auth()->user()->id;
        $vehicle->updated_by = auth()->user()->id;

        $vehicle->save();
            $policy = new VehicleInsurancePolicy();
    $policy->name = ''; // Add appropriate data
    $policy->valid_upto = ''; // Add appropriate data
    $policy->company = ''; // Add appropriate data
    $policy->premium = ''; // Add appropriate data
    $policy->cover = ''; // Add appropriate data
    $policy->vehicle_registration_number = $request->vehicle_no; // Example: using vehicle_no for registration number
    $policy->vehicle_make = ''; // Add appropriate data
    $policy->vehicle_model = ''; // Add appropriate data
    $policy->vehicle_year = ''; // Add appropriate data
    $policy->vehicle_id = $vehicle->vehicle_id; // Assign the vehicle_id to the newly created FleetVehicle ID
    $policy->save();



        return redirect()->route('vehicles')->with('success', 'Fleet Vehicle created successfully');
    }

    public function fleetedit($id)
    {
        // Find the FleetModel by its ID
        $fleetModel = FleetVehicle::findOrFail($id);
        $technicianIds = $fleetModel->pluck('technician_id');
        $policy = VehicleInsurancePolicy::where('vehicle_id', $id)->first();
        $users = User::where('role', 'technician')->where('status', 'active')
            ->whereNotIn('id', $technicianIds)
            ->get();
        $fleet = FleetDetails::where('vehicle_id', $id)->get();
        $vehicles = FleetVehicle::all();

        // dd($fleet);
        if ($fleet) {
            $oil_change = $fleet->where('fleet_key', 'oil_change')->value('fleet_value') ?? '';
            $tune_up = $fleet->where('fleet_key', 'tune_up')->value('fleet_value') ?? '';
            $tire_rotation = $fleet->where('fleet_key', 'tire_rotation')->value('fleet_value') ?? '';
            $breaks = $fleet->where('fleet_key', 'breaks')->value('fleet_value') ?? '';
            $inspection_codes = $fleet->where('fleet_key', 'inspection_codes')->value('fleet_value') ?? '';
            $mileage = $fleet->where('fleet_key', 'mileage')->value('fleet_value') ?? '';
            $registration_expiration_date = $fleet->where('fleet_key', 'registration_expiration_date')->value('fleet_value') ?? '';
            $vehicle_coverage = $fleet->where('fleet_key', 'vehicle_coverage')->value('fleet_value') ?? '';
            $license_plate = $fleet->where('fleet_key', 'license_plate')->value('fleet_value') ?? '';
            $vin_number = $fleet->where('fleet_key', 'vin_number')->value('fleet_value') ?? '';
            $make = $fleet->where('fleet_key', 'make')->value('fleet_value') ?? '';
            $model = $fleet->where('fleet_key', 'model')->value('fleet_value') ?? '';
            $year = $fleet->where('fleet_key', 'year')->value('fleet_value') ?? '';
            $color = $fleet->where('fleet_key', 'color')->value('fleet_value') ?? '';
            $vehicle_weight = $fleet->where('fleet_key', 'vehicle_weight')->value('fleet_value') ?? '';
            $vehicle_cost = $fleet->where('fleet_key', 'vehicle_cost')->value('fleet_value') ?? '';
            $use_of_vehicle = $fleet->where('fleet_key', 'use_of_vehicle')->value('fleet_value') ?? '';
            $repair_services = $fleet->where('fleet_key', 'repair_services')->value('fleet_value') ?? '';
            $ezpass = $fleet->where('fleet_key', 'ezpass')->value('fleet_value') ?? '';
            $service = $fleet->where('fleet_key', 'service')->value('fleet_value') ?? '';
            $additional_service_notes = $fleet->where('fleet_key', 'additional_service_notes')->value('fleet_value') ?? '';
            $last_updated = $fleet->where('fleet_key', 'last_updated')->value('fleet_value') ?? '';
            $epa_certification = $fleet->where('fleet_key', 'epa_certification')->value('fleet_value') ?? '';
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





        return view('fleet.edit', compact('fleet', 'vehicles', 'fleetModel', 'users', 'policy', 'technicianIds', 'oil_change', 'tune_up', 'tire_rotation', 'breaks', 'inspection_codes', 'mileage', 'registration_expiration_date', 'vehicle_coverage', 'license_plate', 'vin_number', 'make', 'model', 'year', 'color', 'vehicle_weight', 'vehicle_cost', 'use_of_vehicle', 'repair_services', 'ezpass', 'service', 'additional_service_notes', 'last_updated', 'epa_certification'));
    }
    public function vehicleupdateinsurance(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required',
            'valid_upto' => 'required',
            'company' => 'required',
            'premium' => 'required',
            'cover' => 'required',
            'vehicle_registration_number' => 'required',
            'vehicle_make' => 'required',
            'vehicle_model' => 'required',
            'vehicle_year' => 'required',
            'vehicle_id' => 'required', // Ensure vehicle_id exists in vehicles table
            'document' => 'nullable', // Optional file upload validation
        ]);

        // Find the policy by id
        $policy = VehicleInsurancePolicy::where('vehicle_id', $id)->first();
        if ($request->hasFile('vehicle_insurance')) {
            $categoryImage = $request->file('vehicle_insurance');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('vehicle_insurance'), $imageName);
        } else {
            $imageName = null;
        }
        // Update policy object with request data
        $policy->name = $request->name;
        $policy->valid_upto = $request->valid_upto;
                $policy->policy_no = $request->policy_no;

        $policy->company = $request->company;
        $policy->premium = $request->premium;
        $policy->cover = $request->cover;
        $policy->vehicle_registration_number = $request->vehicle_registration_number;
        $policy->vehicle_make = $request->vehicle_make;
        $policy->vehicle_model = $request->vehicle_model;
        $policy->vehicle_year = $request->vehicle_year;
        $policy->vehicle_id = $request->vehicle_id;
        $policy->document = $imageName;

        // Handle document upload if provided


        // Save the updated policy
        $policy->save();

        // Redirect back with success message
        return redirect()->route('vehicles')
            ->with('success', 'Policy updated successfully');
    }
    public function fleetupdated(Request $request)
    {
        $userId = $request->input('id');
        $vehicle_id = $request->input('vehicle_id');

        // Validate the incoming request data as needed

        foreach ($request->except('_token', 'id') as $key => $value) {
            // Delete the existing record if it exists
            FleetDetails::where('user_id', $userId)
                ->where('vehicle_id', $vehicle_id)
                ->where('fleet_key', $key)
                ->delete();

            // Create a new record
            FleetDetails::create([
                'user_id' => $userId,
                'vehicle_id' => $vehicle_id,
                'fleet_key' => $key,
                'fleet_value' => $value
            ]);
        }

        return redirect()->back()->with('success', 'Fleet data updated successfully!');
    }

    public function edit(Request $request, $id)
    {
        $vehicle_id = $id;
        $fleet = FleetDetails::where('vehicle_id', $vehicle_id)->first();

        if ($fleet) {
            $oil_change = $fleet->where('fleet_key', 'oil_change')->value('fleet_value') ?? '';
            $tune_up = $fleet->where('fleet_key', 'tune_up')->value('fleet_value') ?? '';
            $tire_rotation = $fleet->where('fleet_key', 'tire_rotation')->value('fleet_value') ?? '';
            $breaks = $fleet->where('fleet_key', 'breaks')->value('fleet_value') ?? '';
            $inspection_codes = $fleet->where('fleet_key', 'inspection_codes')->value('fleet_value') ?? '';
            $mileage = $fleet->where('fleet_key', 'mileage')->value('fleet_value') ?? '';
            $registration_expiration_date = $fleet->where('fleet_key', 'registration_expiration_date')->value('fleet_value') ?? '';
            $vehicle_coverage = $fleet->where('fleet_key', 'vehicle_coverage')->value('fleet_value') ?? '';
            $license_plate = $fleet->where('fleet_key', 'license_plate')->value('fleet_value') ?? '';
            $vin_number = $fleet->where('fleet_key', 'vin_number')->value('fleet_value') ?? '';
            $make = $fleet->where('fleet_key', 'make')->value('fleet_value') ?? '';
            $model = $fleet->where('fleet_key', 'model')->value('fleet_value') ?? '';
            $year = $fleet->where('fleet_key', 'year')->value('fleet_value') ?? '';
            $color = $fleet->where('fleet_key', 'color')->value('fleet_value') ?? '';
            $vehicle_weight = $fleet->where('fleet_key', 'vehicle_weight')->value('fleet_value') ?? '';
            $vehicle_cost = $fleet->where('fleet_key', 'vehicle_cost')->value('fleet_value') ?? '';
            $use_of_vehicle = $fleet->where('fleet_key', 'use_of_vehicle')->value('fleet_value') ?? '';
            $repair_services = $fleet->where('fleet_key', 'repair_services')->value('fleet_value') ?? '';
            $ezpass = $fleet->where('fleet_key', 'ezpass')->value('fleet_value') ?? '';
            $service = $fleet->where('fleet_key', 'service')->value('fleet_value') ?? '';
            $additional_service_notes = $fleet->where('fleet_key', 'additional_service_notes')->value('fleet_value') ?? '';
            $last_updated = $fleet->where('fleet_key', 'last_updated')->value('fleet_value') ?? '';
            $epa_certification = $fleet->where('fleet_key', 'epa_certification')->value('fleet_value') ?? '';
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





        return view('fleet.edit', compact('fleet', 'vehicle_id', 'oil_change', 'tune_up', 'tire_rotation', 'breaks', 'inspection_codes', 'mileage', 'registration_expiration_date', 'vehicle_coverage', 'license_plate', 'vin_number', 'make', 'model', 'year', 'color', 'vehicle_weight', 'vehicle_cost', 'use_of_vehicle', 'repair_services', 'ezpass', 'service', 'additional_service_notes', 'last_updated', 'epa_certification'));
    }

    public function updatefleetdetails(Request $request)
    {
        // Retrieve the vehicle_id from the request
        $vehicleId = $request->input('vehicle_id');
        $auth = auth()->user()->id;
        // Loop through each input and update or create the corresponding FleetDetails record
        $inputs = $request->except('_token', 'vehicle_id');
        foreach ($inputs as $key => $value) {
            // Attempt to find the FleetDetails record for the given vehicle_id and fleet_key
            $fleetDetail = FleetDetails::where('vehicle_id', $vehicleId)
                ->where('fleet_key', $key)
                ->first();

            // If record not found, create a new one
            if (!$fleetDetail) {
                FleetDetails::create([
                    'vehicle_id' => $request->vehicle_id,
                    'fleet_key' => $key,
                    'fleet_value' => $value,
                    'user_id' => $auth,
                ]);
            } else {
                // Otherwise, update the existing record
                $fleetDetail->update(['fleet_value' => $value]);
            }
        }

        return redirect()->back()->with('success', 'Fleet data updated successfully!');
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());        // Validate the form data
        $request->validate([
            'vehicle_description' => 'required|',
            //'vehicle_summary' => 'required|string',
            'technician_id' => 'required|', // Assuming technicians are stored in the users table
        ]);

        // Find the FleetModel by its ID
        $fleetModel = FleetVehicle::findOrFail($id);
        if ($request->hasFile('vehicle_image')) {
            $categoryImage = $request->file('vehicle_image');
            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('vehicle_image'), $imageName);

            // Delete the previous image if it exists
            if ($fleetModel->vehicle_image) {
                $imagePath = public_path('vehicle_image') . '/' . $fleetModel->vehicle_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Update the fleetModel with the new image
            $fleetModel->update([
                'vehicle_image' => $imageName,
            ]);
        }
        // Update the FleetModel with the form data
        $fleetModel->update([
            'vehicle_description' => $request->input('vehicle_description'),
            //  'vehicle_summary' => $request->input('vehicle_summary'),
            'technician_id' => $request->input('technician_id'),
            'vehicle_no' => $request->input('vehicle_no'),
            'vehicle_name' => $request->input('vehicle_name'),


            'updated_at' => now(), // Update the updated_at timestamp
            'updated_by' => Auth::id(), // Set the updated_by column to the authenticated user's ID
        ]);

        // Redirect back with a success message
        return redirect()->route('vehicles')->with('success', 'Fleet model updated successfully.');
    }
}