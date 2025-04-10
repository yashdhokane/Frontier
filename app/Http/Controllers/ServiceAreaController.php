<?php

namespace App\Http\Controllers;

use App\Models\LocationServiceArea;
use Illuminate\Http\Request;

class ServiceAreaController extends Controller
{
    public function index()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 55;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }
        $servicearea = LocationServiceArea::all();
        return view('servicearea.index', compact('servicearea'));
    }
    public function create()
    {
        return view('servicearea.add_servicearea');
    }
    // public function store(Request $request)
    // {
    //     $locationServiceArea = new LocationServiceArea();

    //     $locationServiceArea->area_name = $request->input('area_name');
    //     $locationServiceArea->area_description = $request->input('area_description');
    //     $locationServiceArea->area_radius = $request->input('area_radius');
    //     $locationServiceArea->area_latitude = $request->input('area_latitude');
    //     $locationServiceArea->area_longitude = $request->input('area_longitude');

    //     $locationServiceArea->save();
    //     // dd(1);
    //     $servicearea = LocationServiceArea::all();
    //     return redirect()->route('servicearea.index')->with(['success' => 'Service area location created successfully', 'servicearea' => $servicearea]);
    // }
public function store(Request $request)
{
    $validatedData = $request->validate([
        'area_name' => 'required|string|max:255',
        'area_description' => 'required|string',
        'area_radius' => 'required|integer',
        'area_latitude' => 'required|string',
        'area_longitude' => 'required|string',
    ]);

    $locationServiceArea = LocationServiceArea::create($validatedData);

    // Return JSON response for AJAX requests
    return response()->json([
        'success' => true,
        'message' => 'Service area added successfully.',
        'data' => $locationServiceArea
    ], 201);
}

    public function editservicearea(Request $request)
    {

        // dd(1);

        $servicearea = LocationServiceArea::where('area_id', $request->entry_id)->first();



        // $proreport=$proreport->get();
        //   $render_view= view('promotor_sale_summary',compact('proreport'))->render();
        // echo json_encode($proreport);
        // exit();resources\views\frontend\story_details.blade.php
        $render_view = view('servicearea.edit_servicearea_render', compact('servicearea'))->render();
        // return response()->json(['proreport'=>$proreport]);
        return response()->json(['html' => $render_view]);

    }

    public function viewservicearea(Request $request)
    {

        // dd(1);
       
        $servicearea = LocationServiceArea::where('area_id', $request->entry_id)->first();



        // $proreport=$proreport->get();
        //   $render_view= view('promotor_sale_summary',compact('proreport'))->render();
        // echo json_encode($proreport);
        // exit();resources\views\frontend\story_details.blade.php
        $render_view = view('servicearea.view_servicearea', compact('servicearea'))->render();
        // return response()->json(['proreport'=>$proreport]);
        return response()->json(['html' => $render_view]);

    }






    public function update(Request $request)
    {
        $area_id = $request->input('area_id');

        $locationServiceArea = LocationServiceArea::find($area_id);

        if (!$locationServiceArea) {
            return abort(404); // Or handle the case where the record is not found
        }

        $locationServiceArea->area_name = $request->input('area_name');
        $locationServiceArea->area_description = $request->input('area_description');
        $locationServiceArea->area_radius = $request->input('area_radius');
        $locationServiceArea->area_latitude = $request->input('area_latitude');
        $locationServiceArea->area_longitude = $request->input('area_longitude');

        $locationServiceArea->save();

        return redirect()->route('servicearea.index')->with('success', 'The service area location has been updated successfully.');
    }

}