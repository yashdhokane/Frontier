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
        $leadSources = UserLeadSourceCustomer::all();

        return view('lead.lead-source', ['leadSources' => $leadSources]);
    }


    public function saveLeadSource(Request $request)
    {
        // dd($request);
        $user = auth()->user();
    
        $leadSource = new UserLeadSourceCustomer();
        $leadSource->user_id = $user->id;
        $leadSource->source_name = $request->lead_source;
        $leadSource->added_by = $user->id;
        $leadSource->last_updated_by = $user->id; 
        $leadSource->save();
    
        return response()->json(['message' => 'Lead source saved successfully']);
    }

    public function updateLeadSource(Request $request, $id)
    {
        // dd($id);
        $leadSource = UserLeadSourceCustomer::findOrFail($id);

        $leadSource->source_name = $request->input('lead_source');
        $leadSource->save();

        return response()->json(['message' => 'Lead source updated successfully']);
    }

    public function deleteLeadSource($leadSourceId)
    {
        $leadSource = UserLeadSourceCustomer::findOrFail($leadSourceId);
        $leadSource->delete();

        return response()->json(['message' => 'Lead source deleted successfully']);
    }

    
}



