<?php

namespace App\Http\Controllers;

use App\Models\PredefineReplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class RepliesController extends Controller
{
    public function index()
    {
        $reply = PredefineReplies::all();

        return view('replies.index', compact('reply'));
    }

    
    public function store(Request $request)
    {
        $timezone_name = Session::get('timezone_name', 'UTC');

        $date = Carbon::now($timezone_name);
        
        $formatted_date = $date->format('Y-m-d H:i:s');

        $reply = new PredefineReplies();

        $reply->pt_title = $request->pt_title;
        $reply->pt_content = $request->pt_content;
        $reply->pt_active = 'yes';
        $reply->pt_date_added = $formatted_date;

        $reply->save();
        
        return redirect()->back()->with('success' , 'The reply have been created successfully.');
    }

    public function delete(string $id)
    {
        $reply = PredefineReplies::find($id);

        $reply->delete();
        
        return redirect()->back()->with('success' , 'The reply have been deleted successfully.');
    }

    public function update(Request $request)
    {
       
        $reply = PredefineReplies::findOrFail($request->pt_id);
    
        $timezone_name = Session::get('timezone_name', 'UTC');
        $date = Carbon::now($timezone_name);
        $formatted_date = $date->format('Y-m-d H:i:s');
    
        $reply->pt_title = $request->pt_title;
        $reply->pt_content = $request->pt_content;
        $reply->pt_active = $request->active; 
        $reply->pt_date_added = $formatted_date;
    
        $reply->save();
    
        return redirect()->back()->with('success', 'The reply has been updated successfully.');
    }
}
