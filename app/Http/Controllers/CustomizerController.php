<?php

namespace App\Http\Controllers;

use App\Models\Customizer;
use Illuminate\Http\Request;

class CustomizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cardPositions = Customizer::orderBy('position')->get();
        return view('customizer.dashboard', compact('cardPositions'));
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
