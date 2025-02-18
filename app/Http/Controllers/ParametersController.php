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
use App\Models\JobParameters;
use App\Models\JobParametersMeta;

use App\Mail\ParamMailTech;
use App\Mail\ParamMailCustomer;
use App\Mail\ParamVehicleMailTech;

class ParametersController extends Controller
{

    public function index()
    {
        // Fetch saved filter values for the authenticated user
        $savedFilters = DB::table('filter_values_jobs_parameters')->get()->toArray();

        $jobParameter = JobParameters::orderBy('created_at', 'desc')->with('ParameterMeta')->get();

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

        return view('parameter.index', compact('jobParameter','tagsList','leadSources','appliance','FlagJob','vendor','products','product','manufacture','title', 'serviceCat', 'getProduct', 'technician', 'customer', 'tickets', 'savedFilters'));
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
        $timezone_name = Session::get('timezone_name');
        $TodayDate = Carbon::now($timezone_name);

        // Filters that may have multiple values (array)
        $multiFilters = [
            'title-filter' => 'job_title',
            'priority-filter' => 'priority',
            'job-status-filter' => 'status',
            'technician-select' => 'technician_id',
            'customer-select' => 'customer_id',
            'appliances-filter' => 'appliances_id'
        ];

        foreach ($multiFilters as $filter => $column) {
            if ($request->filled($filter)) {
                $values = (array) $request->input($filter); // Ensure it's always an array
                $query->whereIn($column, $values);
            }
        }

        // Filters with a single value
        $singleFilters = [
            'IsPublished-filter' => 'is_published',
            'IsConfirmed-filter' => 'is_confirmed',
            'warranty-filter' => 'warranty_type'
        ];

        foreach ($singleFilters as $filter => $column) {
            if ($request->filled($filter)) {
                $query->where($column, $request->input($filter));
            }
        }

        // Filtering by job schedule visibility
        if ($request->filled('showOnSchedule-filter')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('show_on_schedule', $request->input('showOnSchedule-filter'));
            });
        }

        // Filtering by type date range
        if ($request->filled('date-type-select')) {
            $startDate = null;
            $endDate = $TodayDate->toDateTimeString(); // End date is always today

            $value = $request->input('date-type-select');

            switch ($value) {
                case 'lastweek':
                    $startDate = $TodayDate->subWeeks(1)->startOfWeek()->toDateTimeString();
                    break;
                case 'last2week':
                    $startDate = $TodayDate->subWeeks(2)->startOfWeek()->toDateTimeString();
                    break;
                case 'last3week':
                    $startDate = $TodayDate->subWeeks(3)->startOfWeek()->toDateTimeString();
                    break;
                case 'lastmonth':
                    $startDate = $TodayDate->subMonth()->startOfMonth()->toDateTimeString();
                    break;
                case 'last6week':
                    $startDate = $TodayDate->subWeeks(6)->startOfWeek()->toDateTimeString();
                    break;
                case 'custom':
                    // Custom handling can be added if needed (use user input)
                    break;
            }

            // Apply date filter to the query
            if ($startDate) {
                $query->whereHas('jobassignname', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date_time', [$startDate, $endDate]);
                });
            }
        }
        
        // Filtering by date range
        if ($request->filled('start-date-filter') || $request->filled('end-date-filter')) {
            $query->whereHas('jobassignname', function ($q) use ($request) {
                $startDate = $request->input('start-date-filter');
                $endDate = $request->input('end-date-filter');

                if (!empty($startDate) && !empty($endDate)) {
                    $q->whereBetween('start_date_time', [$startDate, $endDate]);
                } elseif (!empty($startDate)) {
                    $q->whereDate('start_date_time', '>=', $startDate);
                } elseif (!empty($endDate)) {
                    $q->whereDate('start_date_time', '<=', $endDate);
                }
            });
        }

        // Filtering by customer tags (special case using FIND_IN_SET)
        if ($request->filled('customer-tags-filter')) {
            $selectedTags = (array) $request->input('customer-tags-filter');

            $query->where(function ($q) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $q->orWhereRaw("FIND_IN_SET(?, tag_ids)", [$tag]);
                }
            });
        }

        // Filtering by flagged users (Corrected)
        if ($request->filled('flag-filter')) {
            $flagIds = (array) $request->input('flag-filter');

            $query->whereHas('user', function ($q) use ($flagIds) {
                $q->whereIn('flag_id', $flagIds);
            });
        }

        // Fetch filtered data with related models
        $tickets = $query->with(['user', 'technician', 'jobassignname', 'schedule'])->get();

        // Generate updated table rows
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
       
    public function saveFiltersjob(Request $request)
    {
        DB::beginTransaction();
        try {
            $filters = $request->all();
            $filterId = $request->input('filter_id'); // Get filter ID for update

            if ($filterId) {
                // Update existing filter
                DB::table('job_parameters')
                    ->where('p_id', $filterId)
                    ->update([
                        'p_type' => $filters['type'] ?? null,
                        'p_name' => $filters['filter_name'] ?? null,
                        'updated_at' => now()
                    ]);

                // Delete old meta data and insert new ones
                DB::table('job_parameters_meta')->where('p_id', $filterId)->delete();
                foreach ($filters as $filterName => $filterValue) {
                    $pvalue = is_array($filterValue) ? json_encode($filterValue) : $filterValue;

                    DB::table('job_parameters_meta')->insert([
                        'p_id' => $filterId,
                        'p_name' => $filterName,
                        'p_value' => $pvalue,
                    ]);
                }
            } else {
                // Insert new filter if no ID provided
                $jobParaId = DB::table('job_parameters')->insertGetId([
                    'p_type' => $filters['type'] ?? null,
                    'p_name' => $filters['filter_name'] ?? null,
                    'created_by' => auth()->user()->id ?? null,
                ]);

                foreach ($filters as $filterName => $filterValue) {
                    $pvalue = is_array($filterValue) ? json_encode($filterValue) : $filterValue;

                    DB::table('job_parameters_meta')->insert([
                        'p_id' => $jobParaId,
                        'p_name' => $filterName,
                        'p_value' => $pvalue,
                    ]);
                }
            }

            // Fetch the updated list of saved filters
            $savedFilters = DB::table('job_parameters')->orderBy('created_at', 'desc')->get();

            DB::commit();
            return response()->json([
                'message' => 'Filters saved successfully!',
                'filters' => $savedFilters
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Filter save error: ' . $e->getMessage()); // Log the error
            return response()->json(['error' => 'Failed to save filters.', 'message' => $e->getMessage()], 500);
        }

    }



    public function getfilterdataparameteragainsuserid(Request $request)
    {
        $filterId = $request->input('filter_id');

        if (!$filterId) {
            return response()->json(['error' => 'No filter ID provided'], 400);
        }

        $filter = JobParametersMeta::where('p_id', $filterId)->get();
        
        // If no filter is found, return an error response
        if (!$filter) {
            return response()->json(['error' => 'Filter not found'], 404);
        }

        // Convert meta records into key-value pairs
        // $filterData  = $filter->mapWithKeys(function ($meta) {
        //     return [$meta->p_name => json_decode($meta->p_value, true) ?? $meta->p_value];
        // })->toArray();

        $filterData = [
            'p_id' => $filterId,
            'parameters' => $filter->mapWithKeys(function ($meta) {
                return [$meta->p_name => json_decode($meta->p_value, true) ?? $meta->p_value];
            })->toArray()
        ];

        // Return the response with the filter data
        return response()->json(['data' => $filterData]);
    }

    public function getsavedfilters(Request $request)
    {
        $type = $request->input('type');
        
        $filters = JobParameters::where('p_type', $type)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['filters' => $filters]);
    }

 public function filterJobsByCommand(Request $request)
{
    $keywordString = strtoupper(trim($request->input('keyword'))); // Convert input to uppercase for consistency
    $keywords = explode(" ", $keywordString); // Split input into words

    $query = JobModel::query();
    $timezone_name = Session::get('timezone_name');
    $currentDate = Carbon::now($timezone_name);

    $filterOpenJobs = false;
    $filterLastDays = null;
    $filterNextDays = null;
    $filterPublished = false;
    $filterConfirmed = false;
    $filterOnSchedule = false;

    // Process each keyword
    for ($i = 0; $i < count($keywords); $i++) {
        $word = $keywords[$i];

        if ($word === "OPEN" && isset($keywords[$i + 1]) && $keywords[$i + 1] === "JOBS") {
            $filterOpenJobs = true;
        }

        if (in_array($word, ["LAST", "PREVIOUS"]) && isset($keywords[$i + 1]) && is_numeric($keywords[$i + 1]) && isset($keywords[$i + 2]) && $keywords[$i + 2] === "DAYS") {
            $filterLastDays = (int)$keywords[$i + 1];
        }

        if (in_array($word, ["NEXT", "UPCOMING"]) && isset($keywords[$i + 1]) && is_numeric($keywords[$i + 1]) && isset($keywords[$i + 2]) && $keywords[$i + 2] === "DAYS") {
            $filterNextDays = (int)$keywords[$i + 1];
        }

        if ($word === "JOB" && isset($keywords[$i + 1])) {
            if ($keywords[$i + 1] === "PUBLISHED") {
                $filterPublished = true;
            } elseif ($keywords[$i + 1] === "CONFIRMED") {
                $filterConfirmed = true;
            } elseif ($keywords[$i + 1] === "ON" && isset($keywords[$i + 2]) && $keywords[$i + 2] === "SCHEDULE") {
                $filterOnSchedule = true;
            }
        }
    }

    // Apply filters
    if ($filterOpenJobs) {
        $query->where('status', 'open');
    }

    if ($filterLastDays !== null) {
        $startDate = $currentDate->copy()->subDays($filterLastDays)->startOfDay();
        $endDate = $currentDate->copy()->endOfDay();

        $query->whereHas('schedule', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date_time', [$startDate, $endDate]);
        });
    }

    if ($filterNextDays !== null) {
        $startDate = $currentDate->copy()->startOfDay();
        $endDate = $currentDate->copy()->addDays($filterNextDays)->endOfDay();

        $query->whereHas('schedule', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date_time', [$startDate, $endDate]);
        });
    }

    if ($filterPublished) {
        $query->where('is_published', 'yes');
    }

    if ($filterConfirmed) {
        $query->where('is_confirmed', 'yes');
    }

    if ($filterOnSchedule) {
        $query->whereHas('schedule', function ($q) {
            $q->where('show_on_schedule', 'yes');
        });
    }

    $tickets = $query->with(['user', 'technician', 'jobassignname', 'schedule'])->get();
    $html = view('parameter.filtered_table_rows', compact('tickets'))->render();

    return response()->json(['html' => $html]);
}





}
