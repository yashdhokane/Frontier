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

        return redirect()->back()->with('success' , 'Job Fields saved successfully');
    }

    public function updateField(Request $request)
    {
        $field = SiteJobFields::findOrFail($request->field_id);
        $field->field_name = $request->input('field_name');
        
        $field->update();

        return redirect()->back()->with('success' , 'Job Fields updated successfully');
    }

    public function deleteJobFields(Request $request, $jobFieldsId)
    {
        $jobFields = SiteJobFields::findOrFail($jobFieldsId);

        $jobFields->delete();

        return redirect()->back()->with('success' , 'Job Fields deleted successfully');
    }
}



