<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\UserTagIdCategory;

use Illuminate\Http\Request;

use Carbon\Carbon;



class TagsController extends Controller

{


    public function create()
    {
        $tagsList = UserTagIdCategory::all();
    
        return view('tags.tags-list', ['tagsList' => $tagsList]);
    }

    public function saveTags(Request $request)
    {
        $user = auth()->user();

        $tags = new UserTagIdCategory();
        $tags->tag_name = $request->tags;
        $tags->user_id = $user->id;
        $tags->created_by = $user->id;
        $tags->save();

        return response()->json(['message' => 'Tags saved successfully']);
    }

    public function updateTag(Request $request, $id)
    {
        $tag = UserTagIdCategory::findOrFail($id);
        $tag->tag_name = $request->input('tag');
        $tag->save();

        return response()->json(['message' => 'Tag updated successfully']);
    }
    
    public function deleteTag($tagId)
    {
        $tag = UserTagIdCategory::findOrFail($tagId);
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }
}



