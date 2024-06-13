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
        $variable = Customizer::where('is_active', 'no')->get();
        $cardPositions = Customizer::where('is_active', 'yes')->orderBy('position')->get();
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

        return view('customizer.dashboard', compact('variable', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount'));
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


    public function updateStatus(Request $request)
    {
    dd($request);
        if ($request->has('element_id')) {
             $section = Customizer::where('element_id',$request->element_id)->first();
            $section->is_active = 'no';
        } else {
            $section = Customizer::findOrFail($request->status);
            $section->is_active = 'yes';
        }
        $section->save();

        return redirect()->back()->with('success', 'Section status updated successfully.');
    }
}
