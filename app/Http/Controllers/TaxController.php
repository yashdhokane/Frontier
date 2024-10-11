<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocationState;

class TaxController extends Controller
{
    public function index()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 56;
        
        $permissionCheck =  app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }
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


        return redirect()->route('tax.index')->with('success', 'The tax information has been updated successfully.');
    }


}