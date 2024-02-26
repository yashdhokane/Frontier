<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $job = JobModel::with('user','technician','jobDetails')->latest()->limit(5)->get();
        $totalCalls = JobModel::count();
        $inProgress = JobModel::where('status', 'in_progress')->count();
        $opened = JobModel::where('status', 'open')->count();
        $complete = JobModel::where('status', 'closed')->count();

        $users = User::where('role','technician')->latest()->limit(5)->get();
        foreach ($users as $key => $value) {
            $areaName = [];
            if (isset($value->service_areas) && !empty($value->service_areas)) {
                $service_areas = explode(',', $value->service_areas);
                foreach ($service_areas as $key1 => $value1) {
                    $location_service_area = DB::table('location_service_area')->where('area_id', $value1)->first();
                    if (isset($location_service_area->area_name) && !empty($location_service_area->area_name)) {
                        $areaName[] = $location_service_area->area_name;
                    }
                }
                $users[$key]['area_name'] = implode(', ', $areaName);
            }

        }

        if (Auth::check()) {

            $role = Auth()->user()->role;

            if ($role == 'technician') {
                return view('admin.main',compact('job','users','totalCalls','inProgress','opened','complete'));
            } else if ($role == 'dispatcher') {
                return view('admin.main',compact('job','users','totalCalls','inProgress','opened','complete'));
            } else if ($role == 'admin') {
                return view('admin.main',compact('job','users','totalCalls','inProgress','opened','complete'));
            }

        } else {
            return redirect()->route('login');
        }

    }

}