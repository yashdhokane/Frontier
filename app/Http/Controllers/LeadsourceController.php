<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\UserLeadSourceCustomer;

use Illuminate\Http\Request;

use Carbon\Carbon;



class LeadsourceController extends Controller

{


    public function create()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 57;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }
        $leadSources = UserLeadSourceCustomer::all();

        return view('lead.lead-source', ['leadSources' => $leadSources]);
    }


    public function store(Request $request)
    {
        // dd($request);
        $user = auth()->user()->id;
    
        $leadSource = new UserLeadSourceCustomer();
        $leadSource->source_name = $request->source_name;
        $leadSource->added_by = $user;
        $leadSource->updated_by = $user; 
        $leadSource->save();
    
        return redirect()->back()->with('success' , 'Lead source has been saved successfully.');
    }

    public function updateLeadSource(Request $request)
    {
        // dd($request);
        $leadSource = UserLeadSourceCustomer::findOrFail($request->source_id);

        $leadSource->source_name = $request->input('source_name');
        $leadSource->update();

        return redirect()->back()->with('success' , 'Lead source has been updated successfully.');
    }

    public function deleteLeadSource(Request $request, $leadSourceId)
    {
        $leadSource = UserLeadSourceCustomer::findOrFail($leadSourceId);
        $leadSource->delete();

        return redirect()->back()->with('success' , 'Lead source has been deleted successfully.');
    }

    
}



