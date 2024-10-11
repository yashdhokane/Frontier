<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\SiteLeadSource;

use Illuminate\Http\Request;

use Carbon\Carbon;



class SiteLeadSourceController extends Controller

{


    public function create()
    {
        // $leadSources = SiteLeadSource::all();
        $leadSources = SiteLeadSource::orderBy('created_at', 'desc')->get();

        return view('lead.site-lead-source', ['leadSources' => $leadSources]);
    }

    public function saveLeadSource(Request $request)
    {
        // dd($request);
        $user = auth()->user();
    
        $leadSource = new SiteLeadSource();
        // $leadSource->source_id = $user->id;
        $leadSource->source_name = $request->lead_source;
        $leadSource->added_by = $user->id;
        $leadSource->updated_by = $user->id; 
        $leadSource->count = 0; 
        $leadSource->save();
    
        return response()->json(['message' => 'The lead source has been created successfully.']);
    }

    public function updateLeadSource(Request $request, $source_id)
    {
        // dd($source_id);
        $leadSource = SiteLeadSource::findOrFail($source_id);

        $leadSource->source_name = $request->input('lead_source');
        $leadSource->save();

        return response()->json(['message' => 'The lead source has been updated successfully.']);
    }

    public function deleteLeadSource($leadSourceId)
    {
        $leadSource = SiteLeadSource::findOrFail($leadSourceId);
        $leadSource->delete();

        return response()->json(['message' => 'The lead source has been deleted successfully.']);
    }
    
}



