<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 49;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $event = Event::with('technician')->get();

        $technicianrole = User::where('role', 'technician')->where('status', 'active')->get();

        return view('events.index', compact('event', 'technicianrole'));
    }


    public function destroy(Request $request, $id)
    {
        $event = Event::find($id);

        $event->delete();

        return redirect()->back()->with('success', 'The event was successfully deleted.');
    }

     public function indexiframe()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 49;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $event = Event::with('technician')->get();

        $technicianrole = User::where('role', 'technician')->where('status', 'active')->get();

        return view('events.iframe_events', compact('event', 'technicianrole'));
    }
    public function destroyiframe(Request $request, $id)
    {
        $event = Event::find($id);

        $event->delete();

        return redirect()->back()->with('success', 'The event was successfully deleted.');
    }

}
