<?php




namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\Models\JobModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        // Validation if required
        Admin::create($request->all());

        return redirect()->route('admins.index');
    }

    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        // Validation if required
        $admin->update($request->all());

        return redirect()->route('admins.index');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admins.index');
    }



    //permissions


    public function permissionindex()
    {
        $permissions = DB::table('user_permissions')->orderByDesc('module_id')->get();
        return view('permission.permissionindex', compact('permissions'));
    }

    public function permissiondelete(Request $request)
    {
        $id = $request->input('id');
        $permission = DB::table('permissions')->where('id', $id)->first();

        if (!$permission) {
            return redirect()->back()->with('error', 'Permission not found');
        }

        DB::table('permissions')->where('id', $id)->delete();

        return redirect()->route('permissionindex')->with('success', 'Permission has been deleted successfully.');
    }

    public function permissionstore(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|unique:permissions|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store the permission in the database
        DB::table('permissions')->insert([
            'name' => $request->input('permission_name'),
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('permissionindex')->with('success', 'Permission has been created successfully.');
    }

    public function updateNotification(Request $request)
    {
        // Get user_id and notice_id from the request
        $userId = $request->input('user_id');
        $noticeId = $request->input('notice_id');

        // Update UserNotification model if user_id and notice_id matched
        $notification = UserNotification::where('user_id', $userId)
            ->where('notice_id', $noticeId)
            ->first();

        if ($notification) {
            $notification->is_read = 1;
            $notification->read_at = Carbon::now();
            $notification->save();
        }

        // Return response
        return response()->json(['message' => 'Notification has been updated successfully.']);
    }



 
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->back()->with('error', 'Search query is required.');
        }

     $jobs = JobModel::with(['JobAppliances.Appliances','user'])
    ->select(
        'customer_id',
        'id',
        'job_title as result',
        DB::raw("'Job' as type"),
        DB::raw("CONCAT(job_title, ' - ', status, ' - ', warranty_type) as short_description")
    )
    ->where('job_code', 'LIKE', "%$query%")
    ->orWhere('job_title', 'LIKE', "%$query%")
    ->get();



        // Search users (dispatcher)
        $users = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'Dispatcher' as type"),
            DB::raw("CONCAT('Mobile: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Employee ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'dispatcher')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%") ;
             })->get();

         $customers = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'User' as type"),
            DB::raw("CONCAT('Mob: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Emp ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'customer')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%");
            })->get();


       $technicians = DB::table('users')
    ->select(
        'users.id',
        DB::raw("CAST(users.name AS CHAR) as result"),
        DB::raw("'Technician' as type"),
        DB::raw("CONCAT('Mob: ', users.mobile,
            CASE WHEN users.employee_id IS NOT NULL AND users.employee_id != 0
            THEN CONCAT(' | Emp ID: ', users.employee_id) ELSE '' END) as short_description"),
        DB::raw("(SELECT GROUP_CONCAT(lsa.area_name SEPARATOR ', ') 
                  FROM location_service_area lsa
                  WHERE FIND_IN_SET(lsa.area_id, users.service_areas)) as service_area")
    )
    ->where('users.role', 'technician')
    ->where(function ($q) use ($query) {
        $q->where('users.name', 'LIKE', "%$query%")
            ->orWhere('users.mobile', 'LIKE', "%$query%");
    })
    ->get();


        $admins = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'Admin' as type"),
            DB::raw("CONCAT('Mob: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Emp ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'admin')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%");
              })->get();



        $services = DB::table('services')->select(
            'service_id as id',
            'service_name as result',
            DB::raw("'Service' as type"),
            'service_description as short_description'
        )
            ->where('service_name', 'LIKE', "%$query%") ->get();

        $products = DB::table('products')->select(
            'product_id as id',
            'product_name as result',
            DB::raw("'Product' as type"),
            'product_description as short_description'
        )
            ->where('product_name', 'LIKE', "%$query%") ->get();


        $payments = DB::table('payments')
            ->select(
                'id',
                DB::raw("CAST(invoice_number AS CHAR) as result"),
                DB::raw("'Payment' as type"),
                DB::raw("CONCAT('Date: ', issue_date, ' | Total: ', total) AS short_description")
            )
            ->where('invoice_number', 'LIKE', "%$query%")
            ->orWhere('customer_id', 'LIKE', "%$query%")
            ->orWhere('total', 'LIKE', "%$query%") ->get();

        $manufacturers = DB::table('manufacturers')->select(
            'id',
            DB::raw("CAST(manufacturer_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Manufacturer' as type"),
            DB::raw("CONCAT(LEFT(manufacturer_description, 20), '...') AS short_description")
        )
            ->where('manufacturer_name', 'LIKE', "%$query%") ->get();

        $estimateTemplates = DB::table('estimate_templates')->select(
            'template_id',
            DB::raw("CAST(template_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Estimate Template' as type"),
            DB::raw("CONCAT(LEFT(template_description, 10), '...') AS short_description")
        )
            ->where('template_name', 'LIKE', "%$query%") ->get();
       $siteSettings1 = DB::table('site_settings')->select(
            'id',
            DB::raw("CAST(business_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Site Setting' as type"),
            DB::raw("CONCAT(LEFT(address, 10), '... | Phone: ', phone, ' | Email: ', email) AS short_description")
        )
            ->where('business_name', 'LIKE', "%$query%") ->get();

        $siteWorkingHours = DB::table('site_working_hours')->select(
            'id',
            DB::raw("CAST(day AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Working Hours' as type"),
            DB::raw("CONCAT('Open: ', start_time, ' - ', end_time, ' | Status: ', open_close) AS short_description")
        )
            ->where('day', 'LIKE', "%$query%") ->get();
        $locationServiceAreas = DB::table('location_service_area')->select(
            'area_id',
            DB::raw("CAST(area_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Service Area' as type"),
            DB::raw("CONCAT(LEFT(area_description, 10), '... | Type: ', area_type, ' | Radius: ', area_radius) AS short_description")
        )
            ->where('area_name', 'LIKE', "%$query%") ->get();

        $locationStates = DB::table('location_states')->select(
            'state_id',
            DB::raw("CAST(state_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'State' as type"),
            DB::raw("CONCAT('Code: ', state_code, ' | Tax: ', state_tax, ' | Service Area ID: ', service_area_id) AS short_description")
        )
            ->where('state_name', 'LIKE', "%$query%") ->get();

        // **Manually merge all collections**
        $mergedResults = collect($jobs)
            ->merge($users)
            ->merge($customers)
            ->merge($admins)
            ->merge($technicians)

            ->merge($services)
            ->merge($products)
                 ->merge($payments)
                 ->merge($manufacturers)
  ->merge($estimateTemplates)
  ->merge($siteSettings1)
  ->merge($siteWorkingHours)
  ->merge($locationServiceAreas)
  ->merge($locationStates);




                 

        // **Paginate the merged collection manually**
        $results = $this->paginate($mergedResults, 10);
        $totalResultsCount = $mergedResults->count(); // Get total count before pagination
        $results = $this->paginate($mergedResults, 10); // Paginate
        return view('admin.global_search_render', compact('locationStates','locationServiceAreas','siteWorkingHours','estimateTemplates','manufacturers','payments','jobs', 'users', 'customers', 'technicians', 'admins', 'services', 'products', 'query', 'results', 'totalResultsCount','siteSettings1'));
    }






    public function globalSearchautosuggest(Request $request)
    {
        $query = $request->input('query');

        // Validate query
        if (!$query) {
            return redirect()->back()->with('error', 'Search query is required.');
        }

        // Search jobs
        $jobs = DB::table('jobs')->select(
            'id',  // Use the actual primary key field
            DB::raw("CONCAT('#', CAST(job_code AS CHAR)) as result"),
            DB::raw("'Job' as type"),
            DB::raw("CONCAT(job_title, ' - ', status, ' - ', warranty_type) as short_description")
        )
            ->where('job_code', 'LIKE', "%$query%")
            ->orWhere('job_title', 'LIKE', "%$query%");

        // Search users (dispatcher)
        $users = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'Dispatcher' as type"),
            DB::raw("CONCAT('Mobile: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Employee ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'dispatcher')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%");
            });

        $customers = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'User' as type"),
            DB::raw("CONCAT('Mobile: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Employee ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'customer')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%");
            });

        $technicians = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'Technician' as type"),
            DB::raw("CONCAT('Mobile: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Employee ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'technician')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%");
            });

        $admins = DB::table('users')->select(
            'id',
            DB::raw("CAST(name AS CHAR) as result"),
            DB::raw("'Admin' as type"),
            DB::raw("CONCAT('Mobile: ', mobile,
        CASE WHEN employee_id IS NOT NULL AND employee_id != 0
        THEN CONCAT(' | Employee ID: ', employee_id) ELSE '' END) as short_description")
        )
            ->where('role', 'admin')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%");
            });


        // Search services
        $services = DB::table('services')->select(
            'service_id',
            DB::raw("CAST(service_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Service' as type"),
            DB::raw("CONCAT(LEFT(service_description, 10), '... (', service_code COLLATE utf8mb4_unicode_ci, ')') AS short_description")
        )
            ->where('service_name', 'LIKE', "%$query%");

        $products = DB::table('products')->select(
            'product_id',
            DB::raw("CAST(product_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Product' as type"),
            DB::raw("CONCAT(LEFT(product_description, 10), '... (', product_code COLLATE utf8mb4_unicode_ci, ')') AS short_description")
        )
            ->where('product_name', 'LIKE', "%$query%");
        $payments = DB::table('payments')
            ->select(
                'id',
                DB::raw("CAST(invoice_number AS CHAR) as result"),
                DB::raw("'Payment' as type"),
                DB::raw("CONCAT('Date: ', issue_date, ' | Total: ', total) AS short_description")
            )
            ->where('invoice_number', 'LIKE', "%$query%")
            ->orWhere('customer_id', 'LIKE', "%$query%")
            ->orWhere('total', 'LIKE', "%$query%");

        $manufacturers = DB::table('manufacturers')->select(
            'id',
            DB::raw("CAST(manufacturer_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Manufacturer' as type"),
            DB::raw("CONCAT(LEFT(manufacturer_description, 20), '...') AS short_description")
        )
            ->where('manufacturer_name', 'LIKE', "%$query%");

        $estimateTemplates = DB::table('estimate_templates')->select(
            'template_id',
            DB::raw("CAST(template_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Estimate Template' as type"),
            DB::raw("CONCAT(LEFT(template_description, 10), '...') AS short_description")
        )
            ->where('template_name', 'LIKE', "%$query%");
        $siteSettings = DB::table('site_settings')->select(
            'id',
            DB::raw("CAST(business_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Site Setting' as type"),
            DB::raw("CONCAT(LEFT(address, 10), '... | Phone: ', phone, ' | Email: ', email) AS short_description")
        )
            ->where('business_name', 'LIKE', "%$query%");

        $siteWorkingHours = DB::table('site_working_hours')->select(
            'id',
            DB::raw("CAST(day AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Working Hours' as type"),
            DB::raw("CONCAT('Open: ', start_time, ' - ', end_time, ' | Status: ', open_close) AS short_description")
        )
            ->where('day', 'LIKE', "%$query%");
        $locationServiceAreas = DB::table('location_service_area')->select(
            'area_id',
            DB::raw("CAST(area_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'Service Area' as type"),
            DB::raw("CONCAT(LEFT(area_description, 10), '... | Type: ', area_type, ' | Radius: ', area_radius) AS short_description")
        )
            ->where('area_name', 'LIKE', "%$query%");

        $locationStates = DB::table('location_states')->select(
            'state_id',
            DB::raw("CAST(state_name AS CHAR) COLLATE utf8mb4_unicode_ci as result"),
            DB::raw("'State' as type"),
            DB::raw("CONCAT('Code: ', state_code, ' | Tax: ', state_tax, ' | Service Area ID: ', service_area_id) AS short_description")
        )
            ->where('state_name', 'LIKE', "%$query%");


        // Combine results with pagination
        $results = $jobs->union($users)->union($admins)->union($technicians)->union($payments)->union($customers)->union($services)->union($products)->union($manufacturers)->union($estimateTemplates)->union($siteWorkingHours)->union($locationServiceAreas)->union($locationStates)
            ->paginate(10);  // You can adjust the number of results per page

        return response()->json([
            'query' => $query,
            'results' => $results,
        ]);
    }



    /**
     * Custom pagination function.
     */
    private function paginate($items, $perPage)
    {
        $currentPage = Paginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }
}
