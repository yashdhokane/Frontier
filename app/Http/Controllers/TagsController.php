<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\SiteTags;

use Illuminate\Http\Request;

use Carbon\Carbon;



class TagsController extends Controller

{


    public function create()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 58;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }
        $tagsList = SiteTags::all();
    
        return view('tags.tags-list', ['tagsList' => $tagsList]);
    }

    public function saveTags(Request $request)
    {
        $user = auth()->user();

        $tags = new SiteTags();

        $tags->tag_name = $request->tags;
        $tags->created_by = $user->id;
        $tags->updated_by = $user->id;

        $tags->save();

        return redirect()->back()->with('success' , 'The tags have been created successfully.');
    }

    public function updateTag(Request $request)
    {
        $tag = SiteTags::findOrFail($request->tag_id);
        $tag->tag_name = $request->input('tag_name');

        $tag->update();

        return redirect()->back()->with('success' , 'The tags have been updated successfully.');
    }
    
    public function deleteTag(Request $request ,$tagId)
    {
        $tag = SiteTags::findOrFail($tagId);

        $tag->delete();

        return redirect()->back()->with('success' , 'The tags have been deleted successfully.');
    }
}



