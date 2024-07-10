<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\JobActivity;
use App\Models\JobAssign;
use App\Models\JobModel;
use App\Models\Schedule;
use App\Models\SiteLeadSource;
use App\Models\SiteTags;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    //techncian reorts
    public function technicianreport()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 43;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }
        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        $job = JobModel::sum('gross_total');
        $totalHours = JobAssign::sum('duration');
        $totaldrivingHours = JobAssign::sum('driving_hours');
        $jobcount = JobModel::count();

        $grossTotalByTechnician = [];

        foreach ($technician as $employee) {
            // Sum of the gross_total column for jobs associated with the current employee
            $totalGross = JobModel::where('technician_id', $employee->id)->sum('gross_total');

            // Store the total gross in the array with employee id as key
            $grossTotalByTechnician[$employee->id] = $totalGross;
        }

        $jobCountsByTechnician = [];

        foreach ($technician as $employee) {
            // Count the number of jobs associated with the current employee
            $jobCount = JobModel::where('technician_id', $employee->id)->count();

            // Store the job count in the array with employee id as key
            $jobCountsByTechnician[$employee->id] = $jobCount;
        }

        $jobHours = [];

        foreach ($technician as $employee) {
            // Count the number of jobs associated with the current employee
            $jobCount = JobAssign::where('technician_id', $employee->id)->sum('duration');

            // Store the job count in the array with employee id as key
            $jobHours[$employee->id] = $jobCount;
        }

        $drivingHours = [];

        foreach ($technician as $employee) {
            // Count the number of jobs associated with the current employee
            $jobCount = JobAssign::where('technician_id', $employee->id)->sum('driving_hours');

            // Store the job count in the array with employee id as key
            $drivingHours[$employee->id] = $jobCount;
        }

        return view('reports.technician', compact('technician', 'job', 'jobcount', 'grossTotalByTechnician', 'jobCountsByTechnician', 'totalHours', 'jobHours', 'totaldrivingHours', 'drivingHours'));
    }

    public function employeereport()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 44;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $employees = User::whereNotIn('role', ['technician', 'customer'])->get();


        $job = JobModel::count();
        // Initialize an array to store the job counts for each employee
        $jobCountsByEmployee = [];

        foreach ($employees as $employee) {
            // Count the number of jobs associated with the current employee
            $jobCount = JobModel::where('added_by', $employee->id)->count();

            // Store the job count in the array with employee id as key
            $jobCountsByEmployee[$employee->id] = $jobCount;
        }

        // Initialize an array to store the job counts for each employee
        $jobCountsUpdatedBy = [];

        foreach ($employees as $employee) {
            // Count the number of jobs associated with the current employee
            $jobCount = JobModel::where('updated_by', $employee->id)->count();

            // Store the job count in the array with employee id as key
            $jobCountsUpdatedBy[$employee->id] = $jobCount;
        }

        // Initialize an array to store the job counts for each employee
        $jobCountsClosedBy = [];

        foreach ($employees as $employee) {
            // Count the number of jobs associated with the current employee
            $jobCount = JobModel::where('closed_by', $employee->id)->count();

            // Store the job count in the array with employee id as key
            $jobCountsClosedBy[$employee->id] = $jobCount;
        }


        $alltotalGross = JobModel::sum('gross_total');
        $grossTotalByEmployee = [];

        foreach ($employees as $employee) {
            // Sum of the gross_total column for jobs associated with the current employee
            $totalGross = JobModel::where('added_by', $employee->id)->sum('gross_total');

            // Store the total gross in the array with employee id as key
            $grossTotalByEmployee[$employee->id] = $totalGross;
        }


        $totalActivity = JobActivity::count();
        $activity = [];

        foreach ($employees as $employee) {
            // Sum of the gross_total column for jobs associated with the current employee
            $activityCount = JobActivity::where('user_id', $employee->id)->count();

            // Store the total gross in the array with employee id as key
            $activity[$employee->id] = $activityCount;
        }



        $totalChats = ChatMessage::count();

        $chats = [];

        foreach ($employees as $employee) {
            // Sum of the gross_total column for jobs associated with the current employee
            $activityCount = ChatMessage::where('sender', $employee->id)->count();

            // Store the total gross in the array with employee id as key
            $chats[$employee->id] = $activityCount;
        }

        $employees = $employees->sortByDesc(function ($employee) use ($jobCountsByEmployee) {
            return $jobCountsByEmployee[$employee->id] ?? 0;
        });


        return view('reports.employees', compact('job', 'employees', 'jobCountsByEmployee', 'jobCountsUpdatedBy', 'jobCountsClosedBy', 'grossTotalByEmployee', 'alltotalGross', 'activity', 'totalActivity', 'chats', 'totalChats'));
    }

    public function jobreport()
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 42;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }


        return view('reports.job');
    }

    public function data_report(Request $request)
    {
        $data = $request->type;

        $customerDetails = JobModel::join('users', 'jobs.customer_id', '=', 'users.id')
            ->select(
                DB::raw('COUNT(jobs.id) as total_jobs'),
                DB::raw('SUM(jobs.gross_total) as total_revenue'),
                'jobs.customer_id',
                'users.name'
            )
            ->groupBy('jobs.customer_id')
            ->groupBy('users.name')
            ->get();

        // Fetch all unique zip codes from the job table
        $zipCodeDetails = JobModel::select('zipcode')
            ->selectRaw('COUNT(*) as job_count, SUM(gross_total) as total_gross_total')
            ->groupBy('zipcode')
            ->get();

        $cityDetails = JobModel::select('city')
            ->selectRaw('COUNT(*) as job_count, SUM(gross_total) as total_gross_total')
            ->groupBy('city')
            ->get();

        $stateDetails = JobModel::select('state')
            ->selectRaw('COUNT(*) as job_count, SUM(gross_total) as total_gross_total')
            ->groupBy('state')
            ->get();

        $jobstatus = JobModel::select('status')
            ->selectRaw('COUNT(*) as job_count, SUM(gross_total) as total_gross_total')
            ->groupBy('status')
            ->get();

        $jobs = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(DB::raw('DATE(job_assigned.start_date_time) as date'), DB::raw('SUM(jobs.gross_total) as daily_gross_total'))
            ->groupBy(DB::raw('DATE(job_assigned.start_date_time)'))
            ->get();

        $monthJobs = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(
                DB::raw('MONTH(job_assigned.start_date_time) as month'),
                DB::raw('SUM(jobs.gross_total) as monthly_gross_total')
            )
            ->groupBy(DB::raw('MONTH(job_assigned.start_date_time)'))
            ->get();

        $monthJobscount = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(
                DB::raw('DATE(job_assigned.start_date_time) as date'),
                DB::raw('COUNT(jobs.id) as job_count')
            )
            ->groupBy(DB::raw('DATE(job_assigned.start_date_time)'))
            ->get();


        $daily = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(
                DB::raw('DATE(job_assigned.start_date_time) as date'),
                DB::raw('COUNT(jobs.id) as job_count'),
                DB::raw('SUM(jobs.gross_total) as daily_gross_total')
            )
            ->groupBy(DB::raw('DATE(job_assigned.start_date_time)'))
            ->get();

        $weekly = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(
                DB::raw('YEARWEEK(job_assigned.start_date_time) as week'),
                DB::raw('SUM(jobs.gross_total) as weekly_gross_total'),
                DB::raw('COUNT(jobs.id) as job_count')
            )
            ->groupBy(DB::raw('YEARWEEK(job_assigned.start_date_time)'))
            ->get();

        $monthly = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(
                DB::raw('MONTH(job_assigned.start_date_time) as month'),
                DB::raw('SUM(jobs.gross_total) as weekly_gross_total'),
                DB::raw('COUNT(jobs.id) as job_count')
            )
            ->groupBy(DB::raw('MONTH(job_assigned.start_date_time)'))
            ->get();

        $tagCounts = JobModel::join('site_tags', 'jobs.tag_ids', 'LIKE', DB::raw('CONCAT("%", site_tags.tag_id, "%")'))
            ->select(
                'site_tags.tag_name',
                DB::raw('SUM(jobs.gross_total) as total_gross_total'),
                DB::raw('COUNT(jobs.id) as job_count'),
            )
            ->groupBy('site_tags.tag_id', 'site_tags.tag_name')
            ->get();
        $priorityCounts = JobModel::select(
            'priority',
            DB::raw('SUM(gross_total) as total_gross_total'),
            DB::raw('COUNT(id) as job_count')
        )
            ->groupBy('priority')
            ->get();

        $leadSourceCounts = DB::table('jobs')
            ->join('users', 'jobs.customer_id', '=', 'users.id')
            ->join('site_lead_source', 'users.source_id', '=', 'site_lead_source.source_id')
            ->select('site_lead_source.source_name as lead_source', DB::raw('COUNT(jobs.id) as job_count'), DB::raw('SUM(jobs.gross_total) as total_gross_total'))
            ->groupBy('site_lead_source.source_name')
            ->get();

        $CountsManufacturer = JobModel::join('job_details', 'jobs.id', '=', 'job_details.job_id')
            ->join('manufacturers', 'job_details.manufacturer_id', '=', 'manufacturers.id')
            ->select(
                DB::raw('COUNT(jobs.id) as total_jobs'),
                DB::raw('SUM(jobs.gross_total) as total_revenue'),
                'job_details.manufacturer_id as manu_id',
                'manufacturers.manufacturer_name'
            )
            ->groupBy('job_details.manufacturer_id')
            ->groupBy('manufacturers.manufacturer_name')
            ->get();

        $CountsAppliance = JobModel::join('job_details', 'jobs.id', '=', 'job_details.job_id')
            ->join('appliances', 'job_details.appliance_id', '=', 'appliances.appliance_id')
            ->select(
                DB::raw('COUNT(jobs.id) as total_jobs'),
                DB::raw('SUM(jobs.gross_total) as total_revenue'),
                'job_details.appliance_id',
                'appliances.appliance_name'
            )
            ->groupBy('job_details.appliance_id')
            ->groupBy('appliances.appliance_name')
            ->orderByDesc('total_jobs')
            ->get();


        return view('reports.data_report', compact('data', 'customerDetails', 'zipCodeDetails', 'cityDetails', 'stateDetails', 'jobs', 'monthJobs', 'monthJobscount', 'daily', 'weekly', 'monthly', 'tagCounts', 'priorityCounts', 'leadSourceCounts', 'CountsManufacturer', 'CountsAppliance', 'jobstatus'));
    }
    public function fleetreport()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 46;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        // Fetch users with fleet details where the role is 'technician'
        $users = User::with('fleetDetails')->where('role', 'technician')->get();

        // Extract fleet keys if there are fleet details for at least one user
        $fleetKeys = [];
        foreach ($users as $user) {
            if ($user->fleetDetails) {
                $fleetKeys = array_merge($fleetKeys, $user->fleetDetails->pluck('fleet_key')->toArray());
            }
        }

        // Remove duplicates from the $fleetKeys array
        $fleetKeys = array_unique($fleetKeys);

        return view('reports.fleetreport', compact('users', 'fleetKeys'));
    }
}
