<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Services;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    public function index()
    {
        return view('services.index');
    }


    public function listingServices($category_id = null)
    {
        // If $category_id is provided, filter services by category ID
        $query = Services::orderBy('created_at', 'desc');
        if ($category_id) {
            $query->where('service_category_id', $category_id);
        }

        $service = $query->get();

        return view('services.listing_services', compact('service', 'category_id'));
    }



    public function createServices()
    {
        $services = ServiceCategory::all();
        $manufacturers= Manufacturer::all();
        return view('services.create_services', compact('services','manufacturers'));
    }

   public function storeServices(Request $request)
{
    // dd($request->all());
    // Remove the validation code
    $adminId = Auth::id();
    // Combine hours and minutes
    $serviceOnline = $request->has('service_online') ? 'yes' : 'no';

    // Combine hours and minutes
    $combinedTime = $request->input('hours') . ':' . $request->input('minutes');

    // Save to the database without validation
    Services::create([
        'service_category_id' => $request->input('service_category_id'),
        'service_name' => $request->input('service_name'),
        'service_description' => $request->input('service_description'),
        'service_code' => $request->input('service_code'),
        'service_online' => $serviceOnline,
        'service_for' => $request->input('service_for'),
        'troubleshooting_question1' => $request->input('troubleshooting_question1'),
        'troubleshooting_question2' => $request->input('troubleshooting_question2'),
        'service_image' => $request->file('service_image')->store('images'),
        'service_time' => $combinedTime,
        'created_by' => $adminId,
        'updated_by' => $adminId,
        'service_cost' => $request->input('service_cost'), // Add this line
        'service_quantity' => $request->input('service_quantity'), // Add this line
        'service_discount' => $request->input('service_discount'), // Add this line
        'service_tax' => $request->input('service_tax'), // Add this line
        'service_total' => $request->input('service_total'), // Add this line
    ]);

    // Redirect to the index route with the service_category_id parameter
    return redirect()->route('services.listingServices', ['category_id' => $request->input('service_category_id')])
        ->with('success', 'Service created successfully!');
}
  
    public function editServices($service_id)
    {
        $service = Services::findOrFail($service_id);

        // Retrieve all service categories
        $serviceCategories = ServiceCategory::all();
        $manufacturers= Manufacturer::all();
        // Separate hours and minutes
        list($hours, $minutes) = explode(':', $service->service_time);

        return view('services.edit_services', compact('service','manufacturers', 'hours', 'minutes', 'serviceCategories'));
    }


    public function updateServices(Request $request, $service_id)
{
    // Find the service by ID
    $service = Services::findOrFail($service_id);

    // Combine hours and minutes
    $serviceOnline = $request->has('service_online') ? 'yes' : 'no';
    $combinedTime = $request->input('hours') . ':' . $request->input('minutes');

    // Update service attributes without validation
    $service->update([
        'service_category_id' => $request->input('service_category_id'),
        'service_name' => $request->input('service_name'),
        'service_description' => $request->input('service_description'),
        'service_code' => $request->input('service_code'),
        'service_online' => $serviceOnline,
        'service_for' => $request->input('service_for'),
        'troubleshooting_question1' => $request->input('troubleshooting_question1'),
        'troubleshooting_question2' => $request->input('troubleshooting_question2'),
        'service_image' => $request->file('service_image') ? $request->file('service_image')->store('images') : $service->service_image,
        'service_time' => $combinedTime,
        'updated_by' => Auth::id(),
        'service_cost' => $request->input('service_cost'), // Add this line
        'service_quantity' => $request->input('service_quantity'), // Add this line
        'service_discount' => $request->input('service_discount'), // Add this line
        'service_tax' => $request->input('service_tax'), // Add this line
        'service_total' => $request->input('service_total'), // Add this line
    ]);

    // Redirect to the index route with success message
    return redirect()->route('services.listingServices', ['category_id' => $request->input('service_category_id')])
        ->with('success', 'Service updated successfully!');
}

    public function deleteService($service_id)
    {
        $service = Services::find($service_id);

        if (!$service) {
            // Handle not found case, redirect or show an error message
            return redirect()->back()->with('error', 'Service not found.');
        }

        // Delete the service
        $service->delete();

        // Redirect to the index route with a success message
        return redirect()->back()->with('success', 'Service deleted successfully.');
    }

}
