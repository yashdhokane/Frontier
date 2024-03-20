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
        $manufacturers = Manufacturer::all();
        return view('services.create_services', compact('services', 'manufacturers'));
    }

    public function storeServices(Request $request)
    {


        $adminId = Auth::id();


        // Serialize manufacturer_ids manually
        $manufacturer_ids = implode(',',$request->manufacturer_ids);

        $service = new Service();
        // Save to the database without validation

        $service->service_category_id = $request->service_category_id;
        $service->service_name = $request->service_name;
        $service->service_description = $request->service_description;
        $service->service_code = $request->service_code;
        $service->troubleshooting_question1 = $request->troubleshooting_question1;
        $service->troubleshooting_question2 = $request->troubleshooting_question2;
        $service->service_time = $request->hours;
        $service->created_by = $adminId;
        $service->updated_by = $adminId;
        $service->service_cost = $request->service_cost; // Add this linethis line
        $service->service_discount = $request->service_discount; // Add this line
        $service->service_tax = $request->service_tax; // Add this line
        $service->service_total = $request->service_total; // Add this line
        $service->manufacturer_ids = $manufacturer_ids;
        $service->in_warranty = $request->in_warranty; // Add this line
        $service->job_online = $request->service_online; // Add this line
        $service->estimate_online = $request->estimate_online; // Add this line

        $service->save();

        // Redirect to the index route with the service_category_id parameter
        return redirect()->route('services.listingServices', ['category_id' => $request->input('service_category_id')])
            ->with('success', 'Service created successfully!');
    }



    public function editServices($service_id)
    {

        $services = ServiceCategory::all();

        $manufacturers = Manufacturer::all();

        $service = Services::find($service_id);

        return view('services.edit_services', compact('services', 'manufacturers', 'service'));
    }


    public function updateServices(Request $request, $service_id)
    {
        $adminId = Auth::id();


        // Serialize manufacturer_ids manually
        $manufacturer_ids = json_encode($request->manufacturer_ids);

        $service =  Services::find($service_id);
        // Save to the database without validation

        $service->service_category_id = $request->service_category_id;
        $service->service_name = $request->service_name;
        $service->service_description = $request->service_description;
        $service->service_code = $request->service_code;
        $service->troubleshooting_question1 = $request->troubleshooting_question1;
        $service->troubleshooting_question2 = $request->troubleshooting_question2;
        $service->service_time = $request->hours;
        $service->created_by = $adminId;
        $service->updated_by = $adminId;
        $service->service_cost = $request->service_cost; // Add this linethis line
        $service->service_discount = $request->service_discount; // Add this line
        $service->service_tax = $request->service_tax; // Add this line
        $service->service_total = $request->service_total; // Add this line
        $service->manufacturer_ids = $manufacturer_ids;
        if ($request->in_warranty) {
            $service->in_warranty = $request->in_warranty; // Add this line
        }
        if ($request->service_online) {
            $service->job_online = $request->service_online; // Add this line
        }
        if ($request->estimate_online) {
            $service->estimate_online = $request->estimate_online; // Add this line
        }

        $service->update();

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

    public function inactive(Request $request , $id)
    {
        $service = Services::find($id);

        if (!$service) {
            // Handle not found case, redirect or show an error message
            return redirect()->back()->with('error', 'Service not found.');
        }

        $service->service_active = 'no';

        // Delete the service
        $service->update();

        // Redirect to the index route with a success message
        return redirect()->back()->with('success', 'Service updated successfully.');
    }

    public function active(Request $request , $id)
    {
        $service = Services::find($id);

        if (!$service) {
            // Handle not found case, redirect or show an error message
            return redirect()->back()->with('error', 'Service not found.');
        }

        $service->service_active = 'yes';

        // Delete the service
        $service->update();

        // Redirect to the index route with a success message
        return redirect()->back()->with('success', 'Service updated successfully.');
    }
}
