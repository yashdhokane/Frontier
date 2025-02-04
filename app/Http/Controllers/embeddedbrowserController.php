<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class embeddedbrowserController extends Controller
{
    //
    public function embedded()
    {
        $user = Auth::user();
        $user_tabs = DB::table('user_tabs')
            ->where('user_id', $user->id)
            ->orderBy('id', 'asc')
            ->get();

        // Check if no tabs are found for the user
        if ($user_tabs->isEmpty()) {
            // Default values if user_id not found
            $user_tabs = collect([
                (object) [
                    'user_id' => $user->id,
                    'tab_url' => 'https://gaffis.com/',
                    'tab_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        return view('embeddedbrowser.embedded', compact('user_tabs'));
    }

        public function clearTabs(Request $request)
    {
        $user = auth()->user();

        DB::table('user_tabs')
            ->where('user_id', $user->id)
            ->delete();

        return response()->json(['message' => 'Tabs cleared successfully.']);
    }
    public function getUserTabs()
    {
        $user = Auth::user();
        $user_tabs = DB::table('user_tabs')
            ->where('user_id', $user->id)
            ->orderBy('id', 'asc')
            ->get();

        // Check if no tabs are found for the user
        if ($user_tabs->isEmpty()) {
            // Default values if user_id not found
            $user_tabs = collect([
                (object) [
                    'user_id' => $user->id,
                    'tab_url' => 'https://gaffis.com/',
                    'tab_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        return response()->json($user_tabs);
    }
    public function saveTabData(Request $request)
    {
        // Retrieve data from the query string
        $tab_url = $request->query('tab_url');
        $tab_order = $request->query('tab_order');

        // Validate the parameters
        $validated = $request->validate([
            'tab_url' => 'required|string',
            'tab_order' => 'required|integer',
        ]);

        try {
            // Get the last tab_order for the current user
            $lastTab = DB::table('user_tabs')
                ->where('user_id', Auth::id())
                ->orderBy('tab_order', 'desc') // Get the last tab_order
                ->first();

            // If no tabs exist, set tab_order to 1
            $newTabOrder = $lastTab ? $lastTab->tab_order + 1 : 1;

            // Now save the tab with the new tab_order
            $existingTab = DB::table('user_tabs')
                ->where('user_id', Auth::id())
                ->where('tab_order', $newTabOrder)
                ->first();

            if ($existingTab) {
                // If the tab exists, update it
                DB::table('user_tabs')
                    ->where('user_id', Auth::id())
                    ->where('tab_order', $newTabOrder)
                    ->update([
                        'tab_url' => $validated['tab_url'],
                        'updated_at' => now(),
                    ]);
            } else {
                // If the tab does not exist, insert a new record
                DB::table('user_tabs')->insert([
                    'user_id' => Auth::id(),
                    'tab_url' => $validated['tab_url'],
                    'tab_order' => $newTabOrder,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['message' => 'Tab data saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving tab data', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to save tab data'], 500);
        }
    }
}
