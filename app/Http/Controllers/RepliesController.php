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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
