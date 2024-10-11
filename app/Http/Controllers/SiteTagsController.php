<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\SiteTags;

use Illuminate\Http\Request;

use Carbon\Carbon;



class SiteTagsController extends Controller

{


    public function create()
    {
        // $tagsList = SiteTags::all();
        $tagsList = SiteTags::orderBy('created_at', 'desc')->get();

    
        return view('tags.site-tags', ['tagsList' => $tagsList]);
    }

    public function saveTags(Request $request)
    {
        $user = auth()->user();
    
        $tags = new SiteTags();
        $tags->tag_name = $request->tags;
        $tags->created_by = $user->id;
        $tags->updated_by = $user->id;
        
        $tags->count = 0;
    
        $tags->save();
    
        return response()->json(['message' => 'The tags have been created successfully.']);
    }
    

    public function updateTag(Request $request, $id)
    {
        $tag = SiteTags::findOrFail($id);
        $tag->tag_name = $request->input('tag');
        $tag->save();

        return response()->json(['message' => 'The tags have been updated successfully.']);
    }
    
    public function deleteTag($tagId)
    {
        $tag = SiteTags::findOrFail($tagId);
        $tag->delete();

        return response()->json(['message' => 'The tags have been deleted successfully.']);
    }
}



