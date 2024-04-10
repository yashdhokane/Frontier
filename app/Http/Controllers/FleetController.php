<?php

namespace App\Http\Controllers;

use App\Models\FleetVehicle;
use App\Models\LocationServiceArea;
use App\Models\LocationState;
use App\Models\SiteTags;
use App\Models\User;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicle = FleetVehicle::get();

       return view('fleet.index',compact('vehicle'));
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
        $user = User::where('role','technician')->get();
        $serviceAreas = LocationServiceArea::all();
        $users = User::all();
        //    $roles = Role::all();
        $locationStates = LocationState::all();
        $tags = SiteTags::all(); // Fetch all tags
     
         return view('fleet.create',compact('user','users', 'serviceAreas', 'tags', 'locationStates'));
    }

    public function store(Request $request)
    {
        $vehicle = new FleetVehicle();

        $vehicle->vehicle_description = $request->vehicle_description;
        $vehicle->technician_id = $request->technician_id;
        $vehicle->created_by = auth()->user()->id;
        $vehicle->updated_by = auth()->user()->id;

        $vehicle->save();



        return redirect()->back()->with('success', 'Fleet Vehicle created successfully');
    }
}
