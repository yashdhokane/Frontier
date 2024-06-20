<?php

namespace App\Http\Controllers;

use App\Models\Customizer;
use App\Models\LayoutCustomizer;
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
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $Id = $request->id;
            $layout = LayoutCustomizer::where('id', $Id)->first();
        } else {
            $Id = auth()->user()->id;
            $layout = LayoutCustomizer::where('added_by', $Id)->first();
            if (!$layout) {
                $layout = LayoutCustomizer::first();
            }
        }

        $timezone_name = Session::get('timezone_name');

        if (!$layout) {
            return view('404');
        }
        $variable = Customizer::where('layout_id', $layout->id)->where('is_active', 'no')->get();
        $cardPositions = Customizer::where('layout_id', $layout->id)->where('is_active', 'yes')->orderBy('position')->get();

        $layoutList = LayoutCustomizer::all();

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

        return view('customizer.dashboard', compact('variable', 'cardPositions', 'job', 'paymentopen', 'paymentclose', 'adminCount', 'dispatcherCount', 'technicianCount', 'customerCount', 'layout', 'layoutList'));
    }

    public function savePositions(Request $request)
    {
        $positions = json_decode($request->positions, true);
        $userId = auth()->user()->id;

        foreach ($positions as $position) {
            Customizer::updateOrCreate(
                ['element_id' => $position['element_id']],
                ['position' => $position['position'], 'updated_by' => $userId]
            );
        }

        return redirect()->back()->with('success', 'Positions saved successfully!');
    }


    public function updateStatus(Request $request)
    {
        if ($request->has('element_id')) {
            $section = Customizer::where('element_id', $request->element_id)->first();
            $section->is_active = 'no';
        } else {
            $section = Customizer::findOrFail($request->status);
            $section->is_active = 'yes';
        }
        $section->save();

        return redirect()->back()->with('success', 'Section status updated successfully.');
    }

    public function changeStatus(Request $request)
    {
        $elementId = $request->input('element_id');

        // Assuming you have a model named CardPosition
        $cardPosition = Customizer::where('element_id', $elementId)->first();
        if ($cardPosition) {
            $cardPosition->is_active = 'no'; // or true based on your requirement
            $cardPosition->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Element not found']);
    }

    public function updateLayoutName(Request $request, $id)
    {
        // Validate request

        // Find layout by ID
        $layout = LayoutCustomizer::findOrFail($id);

        // Update layout name
        $layout->layout_name = $request->layout_name;
        $layout->updated_by = auth()->user()->id;
        $layout->save();

        // Redirect back with success message or any other response
        return redirect()->back()->with('success', 'Layout name updated successfully.');
    }
}
