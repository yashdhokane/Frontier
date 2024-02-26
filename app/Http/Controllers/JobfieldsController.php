<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\Jobfields;

use Illuminate\Http\Request;

use Carbon\Carbon;



class JobfieldsController extends Controller

{


    public function create()
    {
        $JobfieldsList = Jobfields::all();

        return view('jobfields.job-fields-list', ['JobfieldsList' => $JobfieldsList]);
    }

   
    public function saveJobfields(Request $request)
    {
        $user = auth()->user();

        $tags = new Jobfields();
        $tags->field_name = $request->jobfields;
        $tags->user_id = $user->id;
        $tags->created_by = $user->id;
        $tags->save();

        return response()->json(['message' => 'Job Fields saved successfully']);
    }

    public function updateField(Request $request, $field_id)
    {
        $field = Jobfields::findOrFail($field_id);
        $field->field_name = $request->input('field-name');
        $field->save();

        return response()->json(['message' => 'Field updated successfully']);
    }

    public function deleteJobFields($jobFieldsId)
    {
        $jobFields = Jobfields::findOrFail($jobFieldsId);
        $jobFields->delete();

        return response()->json(['message' => 'Job field deleted successfully']);
    }
}



