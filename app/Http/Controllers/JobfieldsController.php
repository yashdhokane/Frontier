<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\SiteJobFields;

use Illuminate\Http\Request;

use Carbon\Carbon;



class JobfieldsController extends Controller

{


    public function create()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 59;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }
        $JobfieldsList = SiteJobFields::all();

        return view('jobfields.job-fields-list', ['JobfieldsList' => $JobfieldsList]);
    }

   
    public function saveJobfields(Request $request)
    {
        $user = auth()->user();

        $tags = new SiteJobFields();

        $tags->field_name = $request->field_name;
        $tags->created_by = $user->id;
        $tags->updated_by = $user->id;

        $tags->save();

        return redirect()->back()->with('success' , 'Job fields have been saved successfully.');
    }

    public function updateField(Request $request)
    {
        $field = SiteJobFields::findOrFail($request->field_id);
        $field->field_name = $request->input('field_name');
        
        $field->update();

        return redirect()->back()->with('success' , 'Job fields were updated successfully.');
    }

    public function deleteJobFields(Request $request, $jobFieldsId)
    {
        $jobFields = SiteJobFields::findOrFail($jobFieldsId);

        $jobFields->delete();

        return redirect()->back()->with('success' , 'Job fields were deleted successfully.');
    }
}



