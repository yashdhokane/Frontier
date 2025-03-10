<?php




namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\UserNotification;
use Illuminate\Http\Request;

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

    $jobs = DB::table('jobs')->select(
        'id',
        'job_title as result',
        DB::raw("'Job' as type"),
        DB::raw("CONCAT(job_title, ' - ', status, ' - ', warranty_type) as short_description")
    )
    ->where('job_code', 'LIKE', "%$query%")
    ->orWhere('job_title', 'LIKE', "%$query%")
    ->get();

    $users = DB::table('users')->select(
        'id',
        'name as result',
        DB::raw("'User' as type"),
        DB::raw("CONCAT('Mobile: ', mobile, ' | Customer ID: ', customer_id) as short_description")
    )
    ->where('role', 'dispatcher')
    ->where(function ($q) use ($query) {
        $q->where('name', 'LIKE', "%$query%")
          ->orWhere('mobile', 'LIKE', "%$query%");
    })
    ->get();

    $customers = DB::table('users')->select(
        'id',
        'name as result',
        DB::raw("'Customer' as type"),
        DB::raw("CONCAT('Mobile: ', mobile, ' | Customer ID: ', customer_id) as short_description")
    )
    ->where('role', 'customer')
    ->where(function ($q) use ($query) {
        $q->where('name', 'LIKE', "%$query%")
          ->orWhere('mobile', 'LIKE', "%$query%");
    })
    ->get();

    $services = DB::table('services')->select(
        'service_id as id',
        'service_name as result',
        DB::raw("'Service' as type"),
        'service_description as short_description'
    )
    ->where('service_name', 'LIKE', "%$query%")
    ->get();

    $products = DB::table('products')->select(
        'product_id as id',
        'product_name as result',
        DB::raw("'Product' as type"),
        'product_description as short_description'
    )
    ->where('product_name', 'LIKE', "%$query%")
    ->get();

    // **Manually merge all collections**
    $mergedResults = collect($jobs)
        ->merge($users)
        ->merge($customers)
        ->merge($services)
        ->merge($products);

    // **Paginate the merged collection manually**
    $results = $this->paginate($mergedResults, 10);
$totalResultsCount = $mergedResults->count(); // Get total count before pagination
$results = $this->paginate($mergedResults, 10); // Paginate
    return view('admin.global_search_render', compact('jobs', 'users', 'customers', 'services', 'products', 'query', 'results','totalResultsCount'));
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
        DB::raw("CONCAT('#', CAST(job_title AS CHAR)) as result"),
        DB::raw("'Job' as type"),
        DB::raw("CONCAT(job_title, ' - ', status, ' - ', warranty_type) as short_description")
    )
    ->where('job_code', 'LIKE', "%$query%")
    ->orWhere('job_title', 'LIKE', "%$query%");

    // Search users (dispatcher)
    $users = DB::table('users')->select(
        'id',  // Use the actual primary key field
        DB::raw("CAST(name AS CHAR) as result"),
        DB::raw("'User' as type"),
        DB::raw("CONCAT('Mobile: ', mobile, ' | Customer ID: ', customer_id) as short_description")
    )
    ->where('role', 'dispatcher')
    ->where('name', 'LIKE', "%$query%")
    ->orWhere('mobile', 'LIKE', "%$query%");

    // Search customers
    $customers = DB::table('users')->select(
        'id',  // Use the actual primary key field
        DB::raw("CAST(name AS CHAR) as result"),
        DB::raw("'Customer' as type"),
        DB::raw("CONCAT('Mobile: ', mobile, ' | Customer ID: ', customer_id) as short_description")
    )
    ->where('role', 'customer')
    ->where('name', 'LIKE', "%$query%")
    ->orWhere('mobile', 'LIKE', "%$query%");

    // Search services
    $services = DB::table('services')->select(
        'service_id',  // Use the actual primary key field
        DB::raw("CAST(service_name AS CHAR) as result"),
        DB::raw("'Service' as type"),
        DB::raw("CAST(service_description AS CHAR) as short_description")
    )
    ->where('service_name', 'LIKE', "%$query%");

    // Search products
    $products = DB::table('products')->select(
        'product_id',  // Use the actual primary key field
        DB::raw("CAST(product_name AS CHAR) as result"),
        DB::raw("'Product' as type"),
        DB::raw("CAST(product_description AS CHAR) as short_description")
    )
    ->where('product_name', 'LIKE', "%$query%");

    // Combine results with pagination
    $results = $jobs->union($users)->union($customers)->union($services)->union($products)
        ->paginate(10);  // You can adjust the number of results per page

  return response()->json([
        'query' => $query,
        'results' => $results,
    ]);}


    
}

