<?php

namespace App\Http\Controllers;

use App\Models\LocationCity;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function vendorlist()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 51;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck;
        }

        $vendor = Vendor::all();
        return view('vendor.index', compact('vendor'));
    }

    public function create()
    {
        $location = LocationCity::all();

        return view('vendor.create', compact('location'));
    }
    public function edit($id)
    {

        $vendor = Vendor::find($id);
        if (!$vendor) {
            return view('404');
        }
        return view('vendor.edit', compact('vendor'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_description' => 'required|string',
            'address_line_1' => 'required|string|max:255',
            'city_id' => 'required|integer|exists:location_cities,city_id',
        ]);

        $adminId = Auth::id();



        if ($request->hasFile('vendor_image')) {

            $categoryImage = $request->file('vendor_image');


            $imageName = time() . '_' . $categoryImage->getClientOriginalName();
            $categoryImage->move(public_path('images'), $imageName);
        } else {

            $imageName = null;
        }

        $cities = LocationCity::where('city_id',$request->city_id)->first(); 

        Vendor::create([
            'vendor_name' => $request->input('vendor_name'),
            'vendor_description' => $request->input('vendor_description'),

            'vendor_image' => $imageName,
            'added_by' => $adminId,
            'updated_by' => $adminId,
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'city_id' => $request->input('city_id'),
            'city' => $cities->city,
            'state' => $cities->state_code,
            'zipcode_id' => $cities->zip,
        ]);
        return redirect()->route('vendor.index')->with('success', 'The vendor has been created successfully.');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_description' => 'required|string',
            'address_line_1' => 'required|string|max:255',
            'city_id' => 'required|integer|exists:location_cities,city_id',
        ]);
        $adminId = Auth::id();
    
        // Retrieve the vendor record by ID
        $vendor = Vendor::findOrFail($id);
    
        // Check if a new image was uploaded
        if ($request->hasFile('vendor_image')) {
            $vendorImage = $request->file('vendor_image');
            $imageName = time() . '_' . $vendorImage->getClientOriginalName();
            $vendorImage->move(public_path('images'), $imageName);
    
            // Delete the old image if it exists
            if ($vendor->vendor_image && file_exists(public_path('images/' . $vendor->vendor_image))) {
                unlink(public_path('images/' . $vendor->vendor_image));
            }
    
            // Update the image field with the new file name
            $vendor->vendor_image = $imageName;
        }
    
        // Get city details based on the selected city ID
        $city = LocationCity::where('city_id', $request->city_id)->first();
    
        // Update vendor fields
        $vendor->update([
            'vendor_name' => $request->input('vendor_name'),
            'vendor_description' => $request->input('vendor_description'),
            'added_by' => $adminId,
            'updated_by' => $adminId,
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'city_id' => $request->input('city_id'),
            'is_active' => $request->input('status'),
            'city' => $city->city,
            'state' => $city->state_code,
            'zipcode_id' => $city->zip,
        ]);
    
        // Redirect with a success message
        return redirect()->route('vendor.index')->with('success', 'The vendor has been updated successfully.');
    }
    


    public function search(Request $request)
    {
        $query = $request->input('q');

        $cities = LocationCity::where('city', 'LIKE', '%' . $query . '%')->limit(10)
            ->get(); 

        return response()->json($cities);
    }

    public function delete(Request $request , $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $vendor->delete();

        return response()->json(['success' => 'Vendor deleted successfully.']);
    }

}
