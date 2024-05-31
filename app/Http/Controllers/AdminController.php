<?php




namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\UserNotification;
use Illuminate\Http\Request;

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

    return redirect()->route('permissionindex')->with('success', 'Permission deleted successfully');
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

    return redirect()->route('permissionindex')->with('success', 'Permission created successfully');
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
        return response()->json(['message' => 'Notification updated successfully']);
    }
}

