<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TimezoneController extends Controller
{

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = Auth::user()->id; // 'user()' instead of 'User()'
        $user = User::find($id);
    
        // Update the timezone_id of the user
        $user->timezone_id = $request->timezone_id;
        $user->save(); // Use 'save()' to update the record
    
        // Update session values with the new timezone information
        Session::put('timezone_id', $user->timezone_id);
        Session::put('timezone_name', $user->timezone->timezone_name); // Assuming User model has a 'timezone' relationship
        Session::put('time_interval', $user->timezone->time_interval); // Assuming User model has a 'timezone' relationship
    
        return redirect()->back();
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
