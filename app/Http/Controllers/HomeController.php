<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use App\Models\JobModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use function App\Helpers\getSiteSettings;


class HomeController extends Controller
{
    public function index()
    {
        $timezone_name = Session::get('timezone_name');
        $job = JobModel::with('JobAppliances', 'user', 'technician', 'jobassignname', 'JobAssign', 'jobDetails')
            ->where('created_at', '>', Carbon::now($timezone_name))
            ->latest()->limit(5)->get();


        $paymentopen = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'paid')->orderBy('id', 'desc')

            ->limit(5)->get();


        $paymentclose = Payment::with('JobAppliances', 'user', 'JobModel')->where('status', 'unpaid')->orderBy('id', 'desc')

            ->limit(5)->get();
        $totalCalls = JobModel::count();
        $inProgress = JobModel::where('status', 'in_progress')->count();
        $opened = JobModel::where('status', 'open')->count();
        $complete = JobModel::where('status', 'closed')->count();

        $users = User::where('role', 'technician')->latest()->limit(5)->get();
        //  $siteSettings =$this->getSiteSettings();
        $adminCount = User::where('role', 'admin')->count();
        $dispatcherCount = User::where('role', 'dispatcher')->count();
        $technicianCount = User::where('role', 'technician')->count();
        $customerCount = User::where('role', 'customer')->count();

        $customeruser = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        foreach ($customeruser as $user) {
            $userAddresses = DB::table('user_address')
                ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
                ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
                ->where('user_address.user_id', $user->id)
                ->get();

            $jobs = DB::table('jobs')
                ->where('jobs.customer_id', $user->id)
                ->get();
            $grossTotal = DB::table('payments')
                ->selectRaw('sum(`total`) as grosstotal, `customer_id`')
                ->where('customer_id', $user->id)
                ->groupBy('customer_id')
                ->orderByRaw('grosstotal DESC')
                ->first();

            // Merge user addresses and jobs data into user object
            $user->user_addresses = $userAddresses;
            // dd($user->user_addresses );
            $user->jobs = $jobs;
            $user->gross_total = $grossTotal ? $grossTotal->grosstotal : 0;

        }


        $technicianuser = User::where('role', 'technician')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        foreach ($technicianuser as $key => $user) {
            $areaName = [];
            if (isset($user->service_areas) && !empty($user->service_areas)) {
                $service_areas = explode(',', $user->service_areas);
                foreach ($service_areas as $key1 => $value1) {
                    $location_service_area = DB::table('location_service_area')->where('area_id', $value1)->first();
                    if (isset($location_service_area->area_name) && !empty($location_service_area->area_name)) {
                        $areaName[] = $location_service_area->area_name;
                    }
                }
                $technicianuser[$key]->area_name = implode(', ', $areaName);

                // Retrieve job completion data for the current technician user
                $totalJobsCount = JobModel::where('technician_id', $user->id)->count();

                $completedJobsCount = JobModel::where('technician_id', $user->id)->where('status', 'closed')->count();
                $completionRate = ($totalJobsCount > 0) ? ($completedJobsCount / $totalJobsCount) * 100 : 0;

                // Add job completion data to the technician user object
                $technicianuser[$key]->completed_jobs_count = $completedJobsCount;
                $technicianuser[$key]->total_jobs_count = $totalJobsCount;
                $technicianuser[$key]->completion_rate = $completionRate;
            }
        }

        if (Auth::check()) {

            $role = Auth()->user()->role;

            if ($role == 'technician') {
                return view('admin.main', compact('job', 'paymentopen', 'paymentclose', 'customeruser', 'technicianuser', 'customerCount', 'dispatcherCount', 'technicianCount', 'adminCount', 'users', 'totalCalls', 'inProgress', 'opened', 'complete'));
            } else if ($role == 'dispatcher') {
                return view('admin.main', compact('job', 'paymentopen', 'paymentclose', 'customeruser', 'technicianuser', 'customerCount', 'dispatcherCount', 'technicianCount', 'adminCount', 'users', 'totalCalls', 'inProgress', 'opened', 'complete'));
            } else if ($role == 'admin') {
                return view('admin.main', compact('job', 'paymentopen', 'paymentclose', 'customeruser', 'technicianuser', 'customerCount', 'dispatcherCount', 'technicianCount', 'adminCount', 'users', 'totalCalls', 'inProgress', 'opened', 'complete'));
            } else if ($role == 'superadmin') {
                return view('admin.main', compact('job', 'paymentopen', 'paymentclose', 'customeruser', 'technicianuser', 'customerCount', 'dispatcherCount', 'technicianCount', 'adminCount', 'users', 'totalCalls', 'inProgress', 'opened', 'complete'));
            }


        } else {
            return redirect()->route('login');
        }

    }

    //  public function getSiteSettings($id = 1)
    // {
    //     $siteSettings = DB::table('site_settings')->where('id', $id)->first();

    //     return $siteSettings;
    // }

}