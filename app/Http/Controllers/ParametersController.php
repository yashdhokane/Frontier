<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Manufacturer;
use App\Models\ProductAssigned;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\User;
use App\Models\Payment;
use App\Models\FleetVehicle;
use App\Models\Schedule;
use App\Models\VehicleInsurancePolicy;
use App\Models\JobModel;
use App\Models\SiteJobTitle;
use App\Models\ServiceCategory;
use App\Models\Vendor;
use App\Models\FlagJob;
use App\Models\AppliancesType;
use App\Models\UserLeadSourceCustomer;
use App\Models\SiteTags;

use App\Mail\ParamMailTech;
use App\Mail\ParamMailCustomer;
use App\Mail\ParamVehicleMailTech;

class ParametersController extends Controller
{

    public function index()
    {

        // $userId = auth()->id(); // Get authenticated user ID

        // Fetch saved filter values for the authenticated user
        $savedFilters = DB::table('filter_values_jobs_parameters')
            // ->where('user_id', $userId)
            ->get()
            ->toArray();

        $title = SiteJobTitle::all();
        $serviceCat = ServiceCategory::with('Services')->get();
        $getProduct = Products::whereNotNull('base_price')->where('status', 'Publish')->get();
        $technician = User::where('role', 'technician')->get();
        $customer = User::where('role', 'customer')->get();
        $tickets = JobModel::orderBy('created_at', 'desc')->get();
        
        $manufacture = Manufacturer::all();
        $product = ProductCategory::get();
        $products = Products::orderBy('created_at', 'desc')->get();
        $vendor = Vendor::all();
        $FlagJob = FlagJob::all();
        $appliance = AppliancesType::all();
        $leadSources = UserLeadSourceCustomer::all();
        $tagsList = SiteTags::all();

        return view('parameter.index', compact('tagsList','leadSources','appliance','FlagJob','vendor','products','product','manufacture','title', 'serviceCat', 'getProduct', 'technician', 'customer', 'tickets', 'savedFilters'));
    }
    public function searchcustomersparam(Request $request)
    {


        $query = $request->get('query');
        $id = $request->get('customerId');
        if ($query) {

            $customers = User::where('name', 'like', '%' . $query . '%')
                ->where('role', 'customer')
                ->limit(5)
                ->get();

        } else {

            $customers = User::where('id', $id)
                ->where('role', 'customer')
                // ->limit(5)
                ->first();

        }

        return response()->json($customers);
    }

    public function jobsParamFilter(Request $request)
    {
        $query = JobModel::query();

        // Apply filters dynamically
        if ($request->has('technician-select') && $request->input('technician-select')) {
            $query->where('technician_id', $request->input('technician-select'));
        }
        if ($request->has('customer-select') && $request->input('customer-select')) {
            $query->where('customer_id', $request->input('customer-select'));
        }
        if ($request->has('title-filter') && $request->input('title-filter')) {
            $query->where('job_title', $request->input('title-filter'));
        }
        if ($request->has('priority-filter') && $request->input('priority-filter')) {
            $query->where('priority', $request->input('priority-filter'));
        }
        if ($request->has('warranty-filter') && $request->input('warranty-filter')) {
            $query->where('warranty_type', $request->input('warranty-filter'));
        }
        if ($request->has('IsPublished-filter') && $request->input('IsPublished-filter')) {
            $query->where('is_published', $request->input('IsPublished-filter'));
        }
        if ($request->has('IsConfirmed-filter') && $request->input('IsConfirmed-filter')) {
            $query->where('is_confirmed', $request->input('IsConfirmed-filter'));
        }
        if ($request->has('job-status-filter') && $request->input('job-status-filter')) {
            $query->where('status', $request->input('job-status-filter'));
        }
        if ($request->has('start-date-filter') && $request->has('end-date-filter')) {
            $startDate = $request->input('start-date-filter');
            $endDate = $request->input('end-date-filter');

            if (!empty($startDate) && !empty($endDate)) {
                $query->whereHas('jobassignname', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date_time', [$startDate, $endDate]);
                });
            } elseif (!empty($startDate)) {
                $query->whereHas('jobassignname', function ($q) use ($startDate) {
                    $q->whereDate('start_date_time', '>=', $startDate);
                });
            } elseif (!empty($endDate)) {
                $query->whereHas('jobassignname', function ($q) use ($endDate) {
                    $q->whereDate('start_date_time', '<=', $endDate);
                });
            }
        } 
        if ($request->filled('showOnSchedule-filter')) { 
            $data = $request->input('showOnSchedule-filter');

            $query->whereHas('schedule', function ($q) use ($data) {
                $q->where('show_on_schedule', $data); 
            });
        }
        if ($request->filled('flag-filter')) { 
            $data = $request->input('flag-filter');

            $query->whereHas('user', function ($q) use ($data) {
                $q->where('flag_id', $data); 
            });
        }
        if ($request->has('appliances-filter') && $request->input('appliances-filter')) {
            $query->where('appliances_id', $request->input('appliances-filter'));
            
        }
        if ($request->filled('customer-tags-filter')) {
            $selectedTags = $request->input('customer-tags-filter'); // This is an array from Select2

            $query->where(function ($q) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $q->orWhereRaw("FIND_IN_SET(?, tag_ids)", [$tag]);
                }
            });
        }





        // Fetch filtered data
        $tickets = $query->with(['user', 'technician', 'jobassignname','schedule'])->get();

        // Generate the updated table rows
        $html = view('parameter.filtered_table_rows', compact('tickets'))->render();

        return response()->json(['html' => $html]);
    }

    public function jobsProductParam(Request $request)
    {
        
        $query = Products::query();

        // Apply filters dynamically
        if ($request->has('stock-filter') && $request->input('stock-filter')) {
            $query->where('stock_status', $request->input('stock-filter'));
        }
        if ($request->has('category-filter') && $request->input('category-filter')) {
            $query->where('product_category_id', $request->input('category-filter'));
        }
        if ($request->has('manufacturer-filter') && $request->input('manufacturer-filter')) {
            $query->where('product_manu_id', $request->input('manufacturer-filter'));
        }
        if ($request->has('supppliers-filter') && $request->input('supppliers-filter')) {
            $query->where('vendor_id', $request->input('supppliers-filter'));
        }
        if ($request->has('status-filter') && $request->input('status-filter')) {
            $query->where('status', $request->input('status-filter'));
        }

        // Fetch filtered data
        $products = $query->get();

       // dd($products);
        // Generate the updated table rows
        $html = view('parameter.filtered_table_product', compact('products'))->render();

        return response()->json(['html' => $html]);
    }


    public function indexOld()
    {
        $products = Products::orderBy('created_at', 'desc')->get();

        $manufacture = Manufacturer::all();
        $product = ProductCategory::get();
        $product1 = ProductCategory::get();

        $payments = Payment::with('JobAppliances', 'user', 'JobModel')->latest('id')->get();

        $manufacturer = Manufacturer::where('is_active', 'yes')->get();

        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        $technician = User::where('role', 'technician')->where('status', 'active')->get();
        $timezone_name = Session::get('timezone_name', 'UTC');

        $vehicle = VehicleInsurancePolicy::with('vehicle')
            ->whereNotNull('valid_upto') // Ensure valid_upto is not null
            ->whereHas('vehicle') // Ensure the `vehicle` relationship exists
            ->where(function ($query) use ($timezone_name) {
                $query->where('valid_upto', '<', Carbon::now($timezone_name)) // Expired policies
                    ->orWhereBetween('valid_upto', [Carbon::now($timezone_name), Carbon::now($timezone_name)->addMonth()]); // Policies expiring in the next 1 month
            })
            ->orderBy('valid_upto', 'desc')
            ->get();

        // dd($vehicle);

        $schedules = Schedule::with([
            'JobModel' => function ($query) {
                $query->where('status', 'closed') // Add where clause to filter JobModel with status 'closed'
                    ->with([
                        'user',
                        'jobproductinfohasmany.product',
                        'jobserviceinfohasmany.service',
                        'addresscustomer',
                        'JobAppliances.Appliances.manufacturer',
                        'JobAppliances.Appliances.appliance',
                    ]);
            },
            'technician',
        ])->get();

        // Manually load custom field IDs for each JobModel
        $schedules->each(function ($scheduleItem) {
            $jobModel = $scheduleItem->JobModel;
            if ($jobModel) {
                $jobModel->fieldids = $jobModel->fieldids(); // Load custom field IDs
            }

            // dd($jobModel);
        });
        return view('parameterOld.index', compact('schedules', 'technician', 'vehicle', 'products', 'product1', 'manufacture', 'product', 'payments', 'manufacturer', 'tech'));
    }

    public function sendMessage(Request $request)
    {
        $messageContent = $request->message;
        $jobpublish = JobModel::find($request->job_id);

        if ($jobpublish) {
            $customer = User::find($jobpublish->customer_id); // Get customer
            $tech = User::find($jobpublish->technician_id);   // Get technician
            $jobSchedule = Schedule::where('job_id', $request->job_id)->first(); // Get schedule

            // Prepare mail data
            $mailData = [
                'job' => $jobpublish,
                'customer' => $customer,
                'technician' => $tech,
                'schedule' => $jobSchedule,
                'message' => $messageContent,
            ];

            // Update the job
            $jobpublish->is_published = 'yes';
            $jobpublish->save();


            $recipant = 'thesachinraut@gmail.com';
            // $recipant = 'bawanesumit01@gmail.com';

            // Send mail 'bawanesumit01@gmail.com'
            Mail::to($recipant)->send(new ParamMailTech($mailData));
            Mail::to($recipant)->send(new ParamMailCustomer($mailData));
        }

        return response()->json(['message' => 'Message sent successfully!']);
    }

    public function vehicleMessage(Request $request)
    {
        $messageContent = $request->message;

        $tech = User::find($request->tech_id);

        // Prepare mail data
        $mailData = [
            'technician' => $tech,
            'message' => $messageContent,
        ];



        $recipant = 'thesachinraut@gmail.com';
        // $recipant = 'bawanesumit01@gmail.com';

        // Send mail 'bawanesumit01@gmail.com'
        Mail::to($recipant)->send(new ParamVehicleMailTech($mailData));


        return response()->json(['message' => 'Message sent successfully!']);
    }

    // public function saveFiltersjob(Request $request)
    // {
    //      dd($request->all());
    //     // Serialize the multi-select fields dd($request->all());
    //     $services = $request->input('services', []); // Expecting an array
    //     $products = $request->input('products', []); // Expecting an array

    //     // Convert the arrays to JSON strings before storing
    //     $services_json = !empty($services) ? json_encode($services) : null;
    //     $products_json = !empty($products) ? json_encode($products) : null;

    //     // Insert the filter values into the database
    //     DB::table('filter_values_jobs_parameters')->insert([
    //         'user_id' => Auth::id(),

    //         'filter_name' => $request->input('filter_name'),

    //         'technician' => $request->input('technician'),
    //         'customer' => $request->input('customer'),
    //         'job_title' => $request->input('job_title'),
    //         'priority' => $request->input('priority'),
    //         'warranty' => $request->input('warranty'),
    //         'services' => $services_json,
    //         'products' => $products_json,
    //         'is_published' => $request->input('is_published'),
    //         'is_confirmed' => $request->input('is_confirmed'),
    //         'date' => $request->input('date'),
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json(['message' => 'Filters saved successfully!']);
    // }
public function saveFiltersjob(Request $request)
{
    // Get the new filter values from the request
    $product_filter_stock = $request->input('product_filter_stock');
    $product_filter_category = $request->input('product_filter_category');
    $product_filter_manufacturer = $request->input('product_filter_manufacturer');
    $product_filter_supppliers = $request->input('product_filter_supppliers');
    $product_filter_status = $request->input('product_filter_status');

    $services = $request->input('services', []);
    $products = $request->input('products', []);
    $customer_tags = $request->input('customer_tags', []);

    // Convert array filters to JSON if not empty
    $services_json = !empty($services) ? json_encode($services) : null;
    $products_json = !empty($products) ? json_encode($products) : null;
    $customer_tags_json = !empty($customer_tags) ? json_encode($customer_tags) : null;

    // Insert data into the database
    DB::table('filter_values_jobs_parameters')->insert([
        'user_id' => Auth::id(),
        'filter_name' => $request->input('filter_name'),
        'technician' => $request->input('technician'),
        'customer' => $request->input('customer'),
        'job_title' => $request->input('title'), // Matching payload key
        'priority' => $request->input('priority'),
        'warranty' => $request->input('warranty'),
        'services' => $services_json,
        'products' => $products_json,
        'is_published' => $request->input('is_published'),
        'is_confirmed' => $request->input('is_confirmed'),
        'job_status' => $request->input('job_status'),
        'show_on_schedule' => $request->input('show_on_schedule'),
        'flag' => $request->input('flag'),
        'manufacturer' => $request->input('manufacturer'),
        'appliances' => $request->input('appliances'),
        'customer_tags' => $customer_tags_json,
        'type' => $request->input('type'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        // Include the new product filters in the insert query
        'product_filter_stock' => $product_filter_stock,
        'product_filter_category' => $product_filter_category,
        'product_filter_manufacturer' => $product_filter_manufacturer,
        'product_filter_supppliers' => $product_filter_supppliers,
        'product_filter_status' => $product_filter_status,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Filters saved successfully!']);
}





    public function getfilterdataparameteragainsuserid(Request $request)
    {
        // Fetch the filter details based on the provided filter_id
        $filterId = $request->input('filter_id');



        // Make sure a valid ID is provided
        if (!$filterId) {
            return response()->json(['error' => 'No filter ID provided'], 400);
        }

        // Fetch the filter from the 'filter_value_job_parameters' table based on the filter_id
        $filter = DB::table('filter_values_jobs_parameters')->where('id', $filterId)->first();

        // If no filter is found, return an error response
        if (!$filter) {
            return response()->json(['error' => 'Filter not found'], 404);
        }

        // Assuming the filter includes relevant fields for technician, customer, etc.
        $filterData = [
            'id' => $filter->id,
            'user_id' => $filter->user_id,
            'technician' => $filter->technician,
            'customer' => $filter->customer,
            'job_title' => $filter->job_title,
            'priority' => $filter->priority,
            'warranty' => $filter->warranty,
            'services' => $filter->services,  // Services field (assuming stored in the filter)
            'products' => $filter->products,  // Products field (assuming stored in the filter)
            'is_published' => $filter->is_published,
            'is_confirmed' => $filter->is_confirmed,
            'date' => $filter->date,
            'created_at' => $filter->created_at,
            'updated_at' => $filter->updated_at
        ];

        // Return the response with the filter data
        return response()->json(['data' => $filterData]);
    }
}
