<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocationState;

class TaxController extends Controller
{
    public function index()
    {
        $states = LocationState::all();
        return view('tax.index', compact('states'));
    }

    public function getEditForm(Request $request)
    {
        $stateId = $request->input('id');
        $state = LocationState::find($stateId);

        return view('tax.edit', compact('state'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $stateId = $request->input('state_id');
        $state = LocationState::find($stateId);

        $request->validate([
            //  'state_name' => 'required|string|max:255',
            // 'state_tax' => 'required|numeric',
        ]);

        $state->update([
            'state_name' => $request->input('state_name'),
            'state_tax' => $request->input('state_tax'),
        ]);


        return redirect()->route('tax.index')->with('success', 'Tax information updated successfully');
    }


}