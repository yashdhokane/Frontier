<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\SiteJobFields;

use Illuminate\Http\Request;

use Carbon\Carbon;



class SiteJobFieldsController extends Controller

{


    public function create()
    {
        // $JobfieldsList = SiteJobFields::all();
        $JobfieldsList = SiteJobFields::orderBy('created_at', 'desc')->get();

        return view('jobfields.site-job-fields', ['JobfieldsList' => $JobfieldsList]);
    }

   
    public function saveJobfields(Request $request)
    {
        $user = auth()->user();

        $Jobfields = new SiteJobFields();
        $Jobfields->field_name = $request->jobfields;
        $Jobfields->created_by = $user->id;
        $Jobfields->updated_by = $user->id;
        $Jobfields->count = 0;

        $Jobfields->save();
        // dd($tags);

        return response()->json(['message' => 'Job fields have been saved successfully.']);
    }

    public function updateField(Request $request, $field_id)
    {
        $field = SiteJobFields::findOrFail($field_id);
        $field->field_name = $request->input('field-name');
        $field->save();

        return response()->json(['message' => 'The field has been updated successfully.']);
    }

    public function deleteJobFields($jobFieldsId)
    {
        $jobFields = SiteJobFields::findOrFail($jobFieldsId);
        $jobFields->delete();

        return response()->json(['message' => 'The field has been deleted successfully.']);
    }
}



