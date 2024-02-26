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
    
        return response()->json(['message' => 'Tags saved successfully']);
    }
    

    public function updateTag(Request $request, $id)
    {
        $tag = SiteTags::findOrFail($id);
        $tag->tag_name = $request->input('tag');
        $tag->save();

        return response()->json(['message' => 'Tag updated successfully']);
    }
    
    public function deleteTag($tagId)
    {
        $tag = SiteTags::findOrFail($tagId);
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }
}



