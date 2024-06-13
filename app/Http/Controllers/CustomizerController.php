<?php

namespace App\Http\Controllers;

use App\Models\Customizer;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timezone_name = Session::get('timezone_name');
        $cardPositions = Customizer::orderBy('position')->get();
        $job = Schedule::with('JobModel', 'technician')
            ->where('start_date_time', '>', Carbon::now($timezone_name))
            ->latest()->limit(5)->get();

        $paymentopen = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'paid')->orderBy('id', 'desc')

            ->limit(5)->get();

        $paymentclose = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'unpaid')->orderBy('id', 'desc')

            ->limit(5)->get();


        $adminCount = User::where('role', 'admin')->count();
        $dispatcherCount = User::where('role', 'dispatcher')->count();
        $technicianCount = User::where('role', 'technician')->count();
        $customerCount = User::where('role', 'customer')->count();

        return view('customizer.dashboard', compact('cardPositions','job','paymentopen','paymentclose','adminCount','dispatcherCount','technicianCount','customerCount'));
    }

    public function savePositions(Request $request)
    {
        $positions = json_decode($request->positions, true);

        foreach ($positions as $position) {
            Customizer::updateOrCreate(
                ['element_id' => $position['element_id']],
                ['position' => $position['position']]
            );
        }

        return redirect()->back()->with('success', 'Positions saved successfully!');
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
