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
        if ($permissionCheck !== true) {
            return $permissionCheck; // Handle redirection if permission fails
        }

        // Fetch data using raw SQL query
        $data = DB::table('users as u')
            ->selectRaw("
            u.id AS technician_id,
            u.name AS technician_name,
            IFNULL(SUM(jm.gross_total), 0) AS total_gross,
            IFNULL(COUNT(jm.id), 0) AS job_count,
            IFNULL(SUM(ja.duration), 0) AS total_job_hours,
            IFNULL(SUM(ja.driving_hours), 0) AS total_driving_hours,
            MONTH(ja.start_date_time) AS job_month,
            YEAR(ja.start_date_time) AS job_year
        ")
            ->leftJoin('jobs as jm', 'jm.technician_id', '=', 'u.id')
            ->leftJoin('job_assigned as ja', function ($join) {
                $join->on('ja.technician_id', '=', 'u.id')
                    ->where('ja.assign_status', '=', 'active');
            })
            ->where(
                'u.role',
                '=',
                'technician'
            )
            ->where('u.status', '=', 'active')
            ->groupBy(
                'u.id',
                'job_month',
                'job_year'
            )
            ->get();

        // Total values
        $totalJobRevenue = DB::table('jobs')->sum('gross_total');
        $totalJobCount = DB::table('jobs')->count();
        $totalJobHours = DB::table('job_assigned')->sum('duration');
        $totalDrivingHours = DB::table('job_assigned')->sum('driving_hours');

        return view('reports.technician', [
            'technicians' => $data,
            'totalJobRevenue' => $totalJobRevenue,
            'totalJobCount' => $totalJobCount,
            'totalJobHours' => $totalJobHours,
            'totalDrivingHours' => $totalDrivingHours,
        ]);
    }


    public function technicianreportbyajax(Request $request)
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 43;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck !== true) {
            return $permissionCheck;
        }

        // Apply filters
        $technicianQuery = DB::table('users as u')
            ->selectRaw("
            u.id AS technician_id,
            u.name AS technician_name,
            IFNULL(SUM(j.gross_total), 0) AS total_gross,
            IFNULL(COUNT(j.id), 0) AS job_count,
            IFNULL(SUM(ja.duration), 0) AS total_job_hours,
            IFNULL(SUM(ja.driving_hours), 0) AS total_driving_hours
        ")
            ->leftJoin('jobs as j', 'j.technician_id', '=', 'u.id')
            ->leftJoin('job_assigned as ja', 'ja.technician_id', '=', 'u.id')
            ->where('u.role', '=', 'technician')
            ->where(
                'u.status',
                '=',
                'active'
            );

        if ($request->has('filter_type')) {
            if ($request->filter_type === 'date_range' && $request->from_date && $request->to_date) {
                $technicianQuery->whereBetween('ja.start_date_time', [$request->from_date, $request->to_date]);
            } elseif ($request->filter_type === 'month' && $request->month) {
                $technicianQuery->whereMonth('ja.start_date_time', $request->month);
            } elseif ($request->filter_type === 'year' && $request->year) {
                $technicianQuery->whereYear('ja.start_date_time', $request->year);
            }
        }

        $technicianQuery->groupBy('u.id', 'u.name');

        // Fetch technician data
        $technicians = $technicianQuery->get();

        // Summarize totals
        $totalJobRevenue = JobModel::sum('gross_total');
        $totalJobCount = JobModel::count();
        $totalJobHours = JobAssign::sum('duration');
        $totalDrivingHours = JobAssign::sum('driving_hours');

        $view1 = view('reports.technician_subreport_ajxc', compact('technicians', 'totalJobRevenue', 'totalJobCount'))->render();
        $view2 = view('reports.technician_subreport_ajxc2', compact('technicians', 'totalJobHours', 'totalDrivingHours'))->render();

        return response()->json([
            'technician_table_html' => $view1,
            'technician1_table_html' => $view2,
        ]);
    }


   public function employeereport(Request $request)
    {
        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 44;

        // Check permission
        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck !== true) {
            return $permissionCheck; // Handle permission errors
        }

        // Get filter values from the request
        $dataFilter = $request->input('dataFilter');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $selectMonth = $request->input('selectMonth');
        $selectYear = $request->input('selectYear');

        // Base query to get employees excluding technicians and customers
        $employees = User::whereNotIn('role', ['technician', 'customer']);

        // Apply date filters if dataFilter is 'date_range'
        if ($dataFilter == 'date_range' && $fromDate && $toDate) {
            $employees->whereHas('jobAssigned', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('job_assigned.start_date_time', [$fromDate, $toDate])
                    ->where('job_assigned.assign_status', 'active');
            });
        }
        // Apply month and year filters if dataFilter is 'month' or 'year'
        elseif ($dataFilter == 'month' && $selectMonth) {
            $employees->whereHas('jobAssigned', function ($query) use ($selectMonth) {
                $query->whereMonth('job_assigned.start_date_time', $selectMonth)
                    ->where('job_assigned.assign_status', 'active');
            });
        } elseif ($dataFilter == 'year' && $selectYear) {
            $employees->whereHas('jobAssigned', function ($query) use ($selectYear) {
                $query->whereYear('job_assigned.start_date_time', $selectYear)
                    ->where('job_assigned.assign_status', 'active');
            });
        }

        // Get the filtered employees
        $employees = $employees->get();


        // Initialize arrays to store job counts and sums for each employee
        $jobCountsByEmployee = [];
        $jobCountsUpdatedBy = [];
        $jobCountsClosedBy = [];
        $grossTotalByEmployee = [];
        $activity = [];
        $chats = [];

        // Calculate stats for each employee
        foreach ($employees as $employee) {
            $jobCountsByEmployee[$employee->id] = JobModel::where('added_by', $employee->id)->count();
            $jobCountsUpdatedBy[$employee->id] = JobModel::where('updated_by', $employee->id)->count();
            $jobCountsClosedBy[$employee->id] = JobModel::where('closed_by', $employee->id)->count();
            $grossTotalByEmployee[$employee->id] = JobModel::where('added_by', $employee->id)->sum('gross_total');
            $activity[$employee->id] = JobActivity::where('user_id', $employee->id)->count();
            $chats[$employee->id] = ChatMessage::where('sender', $employee->id)->count();
        }

        // Calculate total gross for the filtered jobs (only those that match the filters)
        $alltotalGross = JobModel::whereIn('added_by', $employees->pluck('id'))->sum('gross_total');

        // Calculate total job activities
        $totalActivity = JobActivity::whereIn('user_id', $employees->pluck('id'))->count();

        // Get total chat messages
        $totalChats = ChatMessage::whereIn('sender', $employees->pluck('id'))->count();

        // Count the total number of jobs
        $job = JobModel::whereIn('added_by', $employees->pluck('id'))->count();

        // Sort employees by job count
        $employees = $employees->sortByDesc(function ($employee) use ($jobCountsByEmployee) {
            return $jobCountsByEmployee[$employee->id] ?? 0;
        });

        // If it's an AJAX request, return only the partial HTML for the employee report as JSON
        if ($request->ajax()) {
            $html = view('reports.employees_table_report_by_ajax', compact(
                'employees',
                'jobCountsByEmployee',
                'jobCountsUpdatedBy',
                'jobCountsClosedBy',
                'grossTotalByEmployee',
                'alltotalGross',
                'activity',
                'totalActivity',
                'chats',
                'totalChats',
                'job'  // Pass job variable for AJAX requests
            ))->render();

            return response()->json($html);
        }

        // If not an AJAX request, return the full view
        return view('reports.employees', compact(
            'job',
            'employees',
            'jobCountsByEmployee',
            'jobCountsUpdatedBy',
            'jobCountsClosedBy',
            'grossTotalByEmployee',
            'alltotalGross',
            'activity',
            'totalActivity',
            'chats',
            'totalChats'
        ));
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
        $jobstatus = JobModel::selectRaw("
        CASE
            WHEN is_published = 'yes' THEN 'open'
            ELSE status
        END as status,
        COUNT(*) as job_count,
        SUM(gross_total) as total_gross_total
    ")
            ->groupByRaw("
        CASE
            WHEN is_published = 'yes' THEN 'open'
            ELSE status
        END
    ")
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

    public function fetch_data_report(Request $request)
    {
        $type = $request->type;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $month = $request->month;
        $year = $request->year;

        $query = JobModel::join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
            ->select(DB::raw('DATE(job_assigned.start_date_time) as date'), DB::raw('SUM(jobs.gross_total) as daily_gross_total'));

        // Filters
        if ($type === 'date_range' && $fromDate && $toDate) {
            $query->whereBetween('job_assigned.start_date_time', [$fromDate, $toDate]);
        } elseif ($type === 'month' && $month) {
            $query->whereMonth('job_assigned.start_date_time', $month);
        } elseif ($type === 'year' && $year) {
            $query->whereYear('job_assigned.start_date_time', $year);
        }

        $query->groupBy(DB::raw('DATE(job_assigned.start_date_time)'));

        $jobs = $query->get();



        // Return table dynamically
        return response()->view('reports.data_report_fetch_ajax', compact('jobs'));
    }

    public function status_data_report(Request $request)
    {
        $query = JobModel::selectRaw("
    CASE
        WHEN is_published = 'yes' THEN 'open'
        ELSE status
    END as status,
    COUNT(*) as job_count,
    SUM(gross_total) as total_gross_total,
    MAX(created_at) as revenue_date  -- Add this to get the latest date of revenue for each status
")
            ->groupByRaw("
        CASE
            WHEN is_published = 'yes' THEN 'open'
            ELSE status
        END
    ");


        // Apply filters based on request parameters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $jobstatus = $query->get();

        // Pass the request parameters to the view
        return response()->json(view('reports.status_table', compact('jobstatus', 'request'))->render());
    }

    public function job_tags_data_report(Request $request)
    {
        // Join the tables and perform the query
        $query = JobModel::join('site_tags', 'jobs.tag_ids', 'LIKE', DB::raw('CONCAT("%", site_tags.tag_id, "%")'))
            ->whereNotNull('jobs.tag_ids') // Skip null tag_ids
            ->where('jobs.tag_ids', '!=', '') // Skip empty tag_ids
            ->select(
                'site_tags.tag_name',
                DB::raw('SUM(jobs.gross_total) as total_gross_total'),
                DB::raw('COUNT(jobs.id) as job_count')
            )
            ->groupBy('site_tags.tag_id', 'site_tags.tag_name');

        // Apply filters based on request parameters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('jobs.created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('month')) {
            $query->whereMonth('jobs.created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('jobs.created_at', $request->year);
        }

        // Execute the query and get the result
        $tagCounts = $query->get();

        // Pass the request parameters to the view
        return response()->json(view('reports.jobs_tags', compact('tagCounts', 'request'))->render());
    }
    public function customer_data_report(Request $request)
    {
        // Start building the query
        $query = JobModel::join('users', 'jobs.customer_id', '=', 'users.id')
            ->join('schedule', 'jobs.id', '=', 'schedule.job_id') // Join with the schedule table
            ->select(
                DB::raw('COUNT(jobs.id) as total_jobs'),
                DB::raw('SUM(jobs.gross_total) as total_revenue'),
                'jobs.customer_id',
                'users.name',
                DB::raw('MIN(schedule.start_date_time) as start_date_time'), // Use MIN to aggregate
                DB::raw('MAX(schedule.end_date_time) as end_date_time') // Use MAX to aggregate
            )
            ->groupBy('jobs.customer_id', 'users.name');

        // Apply filters based on request parameters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('schedule.start_date_time', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('month')) {
            $month = (int) $request->month;
            $query->whereMonth('schedule.start_date_time', $month);
        }

        if ($request->filled('year')) {
            $year = (int) $request->year;
            $query->whereYear('schedule.start_date_time', $year);
        }

        // Execute the query and get the result
        $customerDetails = $query->get();

        // Pass the data to the view
        return response()->json(view('reports.customer', compact('customerDetails', 'request'))->render());
    }


    public function zipcode_data_report(Request $request)
    {
        // Start building the query
        $query = JobModel::join('schedule', 'jobs.id', '=', 'schedule.job_id')
            ->select(
                'jobs.zipcode',
                DB::raw('COUNT(jobs.id) as job_count'),
                DB::raw('SUM(jobs.gross_total) as total_gross_total')
            )
            ->groupBy('jobs.zipcode'); // Group by zipcode

        // Apply filters based on request parameters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('schedule.start_date_time', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('month')) {
            $month = (int) $request->month; // Ensure month is an integer
            $query->whereMonth('schedule.start_date_time', $month);
        }

        if ($request->filled('year')) {
            $year = (int) $request->year; // Ensure year is an integer
            $query->whereYear('schedule.start_date_time', $year);
        }

        // Execute the query and get the result
        $zipCodeDetails = $query->get();

        // Pass the data to the view
        return response()->json(view('reports.zipcode', compact('zipCodeDetails', 'request'))->render());
    }
public function manufacturers_data_report(Request $request)
{
    // Start building the query
    $query = DB::table('jobs')
        ->select(
            DB::raw('COUNT(jobs.id) as total_jobs'),
            DB::raw('SUM(jobs.gross_total) as total_revenue'),
            'manufacturers.id as manufacturer_id',
            'manufacturers.manufacturer_name'
        )
        ->join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
        ->join('schedule', 'jobs.id', '=', 'schedule.job_id') // Join with the schedule table
        ->join('job_appliance', 'jobs.id', '=', 'job_appliance.job_id')
        ->join('user_appliances', 'job_appliance.appliance_id', '=', 'user_appliances.appliance_id')
        ->join('manufacturers', 'manufacturers.id', '=', 'user_appliances.manufacturer_id') // Join with manufacturers
        ->where('job_assigned.assign_status', 'active') // Filter by active job status
        ->where('schedule.start_date_time', '>=', '2024-12-01 00:00:00') // Filter by start date

        // Apply dynamic date filters based on the request
        ->when($request->filled('from_date') && $request->filled('to_date'), function ($query) use ($request) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $query->whereBetween('schedule.start_date_time', ["{$fromDate} 00:00:00", "{$toDate} 23:59:59"]);
        })

        // Apply dynamic filters based on month and year if provided
        ->when($request->filled('month'), function ($query) use ($request) {
            $month = (int) $request->month;
            $query->whereMonth('schedule.start_date_time', $month);
        })
        ->when($request->filled('year'), function ($query) use ($request) {
            $year = (int) $request->year;
            $query->whereYear('schedule.start_date_time', $year);
        })

        // Group by manufacturer ID and manufacturer name
        ->groupBy('user_appliances.manufacturer_id', 'manufacturers.manufacturer_name');

    // Execute the query and get the result
    try {
        $CountsManufacturer = $query->get();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Database query failed', 'message' => $e->getMessage()]);
    }

    // Return the result as a response (you can modify the view as needed)
    return response()->json(view('reports.manufacturers', compact('CountsManufacturer', 'request'))->render());
}


public function appliances_data_report(Request $request)
{
    // Start the base query
    $query = DB::table('jobs')
        ->select(
            DB::raw('COUNT(jobs.id) AS total_jobs'),
            DB::raw('SUM(jobs.gross_total) AS total_revenue'),
            'job_appliance.appliance_id',
            'user_appliances.appliance_type_id',
            'appliance_type.appliance_name'
        )
        ->join('job_assigned', 'jobs.id', '=', 'job_assigned.job_id')
        ->join('schedule', 'jobs.id', '=', 'schedule.job_id')
        ->join('job_appliance', 'jobs.id', '=', 'job_appliance.job_id')
        ->join('user_appliances', 'job_appliance.appliance_id', '=', 'user_appliances.appliance_id')
        ->join('appliance_type', 'appliance_type.appliance_type_id', '=', 'user_appliances.appliance_type_id')
        ->where('job_assigned.assign_status', 'active'); // Default filter (active jobs)

    // Dynamically add date filters if the request has from_date and to_date
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
        $query->whereBetween('schedule.start_date_time', ["{$fromDate} 00:00:00", "{$toDate} 23:59:59"]);
    }

    // Dynamically filter by month if the request has month
    elseif ($request->filled('month')) {
        $month = (int) $request->month; // Ensure it's an integer
        $query->whereMonth('schedule.start_date_time', $month);
    }

    // Dynamically filter by year if the request has year
    elseif ($request->filled('year')) {
        $year = (int) $request->year; // Ensure it's an integer
        $query->whereYear('schedule.start_date_time', $year);
    }

    // Add the group by clause to the query, including all non-aggregated columns
    $query->groupBy(
        'user_appliances.appliance_type_id',
        'job_appliance.appliance_id',
        'appliance_type.appliance_name'
    );

    // Execute the query and fetch the results
    try {
        $CountsAppliance = $query->get();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Database query failed', 'message' => $e->getMessage()]);
    }

    // Return the results as a response (you can modify the view as needed)
    return response()->json(view('reports.appliances', compact('CountsAppliance', 'request'))->render());
}







    public function job_lead_source_data_report(Request $request)
    {
        // Start building the query and join the schedule table
        $query = DB::table('jobs')
            ->join('users', 'jobs.customer_id', '=', 'users.id')
            ->join('site_lead_source', 'users.source_id', '=', 'site_lead_source.source_id')
            ->join('schedule', 'jobs.id', '=', 'schedule.job_id')  // Join the schedule table to get start_date_time, end_date_time, created_at
            ->select(
                'site_lead_source.source_name as lead_source',
                DB::raw('COUNT(jobs.id) as job_count'),
                DB::raw('SUM(jobs.gross_total) as total_gross_total'),
                'schedule.created_at',
                'schedule.start_date_time',
                'schedule.end_date_time'
            )
            ->groupBy('site_lead_source.source_name')
            ->groupBy('schedule.created_at')  // You should group by the schedule.created_at as well
            ->groupBy('schedule.start_date_time')
            ->groupBy('schedule.end_date_time');

        // Apply filters based on request parameters (from_date and to_date)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $query->whereBetween('schedule.start_date_time', [$fromDate, $toDate]);  // Filter by start_date_time
        }

        // Apply filter by month
        if ($request->filled('month')) {
            $month = (int) $request->month; // Ensure it's an integer
            $query->whereMonth('schedule.start_date_time', $month);  // Filter by month
        }

        // Apply filter by year
        if ($request->filled('year')) {
            $year = (int) $request->year; // Ensure it's an integer
            $query->whereYear('schedule.start_date_time', $year);  // Filter by year
        }

        // Execute the query and get the result
        $leadSourceCounts = $query->get(); // Call get() once at the end

        // Pass the data to the view (as JSON)
        return response()->json(view('reports.job_lead_source', compact('leadSourceCounts', 'request'))->render());
    }



    public function priority_data_report(Request $request)
    {
        // Start building the query and join the schedule table
        $query = JobModel::join('schedule', 'jobs.id', '=', 'schedule.job_id')  // Join the schedule table to access start_date_time and end_date_time
            ->select(
                'jobs.priority',
                DB::raw('SUM(jobs.gross_total) as total_gross_total'),
                DB::raw('COUNT(jobs.id) as job_count'),
                DB::raw('MAX(schedule.start_date_time) as start_date_time'),  // Get the maximum start_date_time
                DB::raw('MAX(schedule.end_date_time) as end_date_time')  // Get the maximum end_date_time
            )
            ->groupBy('jobs.priority');  // Only group by priority

        // Apply filters based on request parameters (from_date and to_date)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $query->whereBetween('schedule.start_date_time', [$fromDate, $toDate]);  // Filter by start_date_time
        }

        // Apply filter by month
        if ($request->filled('month')) {
            $month = (int) $request->month; // Ensure it's an integer
            $query->whereMonth('schedule.start_date_time', $month);  // Filter by month
        }

        // Apply filter by year
        if ($request->filled('year')) {
            $year = (int) $request->year; // Ensure it's an integer
            $query->whereYear('schedule.start_date_time', $year);  // Filter by year
        }

        // Execute the query and get the result
        $priorityCounts = $query->get(); // Call get() once at the end

        // Pass the data to the view (as JSON)
        return response()->json(view('reports.priority', compact('priorityCounts', 'request'))->render());
    }
    public function city_data_report(Request $request)
    {
        // Start building the query and join the schedule table
        $query = JobModel::join('schedule', 'jobs.id', '=', 'schedule.job_id')  // Join the schedule table
            ->select(
                'jobs.city',
                DB::raw('COUNT(*) as job_count'),
                DB::raw('SUM(jobs.gross_total) as total_gross_total'),
                DB::raw('MAX(schedule.start_date_time) as start_date_time'),  // Use aggregate function for start_date_time
                DB::raw('MAX(schedule.end_date_time) as end_date_time')  // Use aggregate function for end_date_time
            )
            ->groupBy('jobs.city');  // Group by city

        // Apply filters based on request parameters (from_date and to_date)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $query->whereBetween('schedule.start_date_time', [$fromDate, $toDate]);  // Filter by start_date_time
        }

        // Apply filter by month
        if ($request->filled('month')) {
            $month = (int) $request->month; // Ensure it's an integer
            $query->whereMonth('schedule.start_date_time', $month);  // Filter by month
        }

        // Apply filter by year
        if ($request->filled('year')) {
            $year = (int) $request->year; // Ensure it's an integer
            $query->whereYear('schedule.start_date_time', $year);  // Filter by year
        }

        // Execute the query and get the result
        $cityDetails = $query->get(); // Call get() once at the end

        // Pass the data to the view (as JSON)
        return response()->json(view('reports.city', compact('cityDetails', 'request'))->render());
    }


    public function state_data_report(Request $request)
    {
        // Start building the query and join the schedule table
        $query = JobModel::join('schedule', 'jobs.id', '=', 'schedule.job_id')  // Join with schedule table
            ->select(
                'jobs.state',  // Select the state
                DB::raw('COUNT(*) as job_count'),  // Count of jobs
                DB::raw('SUM(jobs.gross_total) as total_gross_total'),  // Sum of gross total
                DB::raw('MAX(schedule.start_date_time) as start_date_time'),  // Use aggregate function for start_date_time
                DB::raw('MAX(schedule.end_date_time) as end_date_time')  // Use aggregate function for end_date_time
            )
            ->groupBy('jobs.state');  // Group by state

        // Apply filters based on request parameters (from_date and to_date)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $query->whereBetween('schedule.start_date_time', [$fromDate, $toDate]);  // Filter by start_date_time
        }

        // Apply filter by month
        if ($request->filled('month')) {
            $month = (int) $request->month; // Ensure it's an integer
            $query->whereMonth('schedule.start_date_time', $month);  // Filter by month
        }

        // Apply filter by year
        if ($request->filled('year')) {
            $year = (int) $request->year; // Ensure it's an integer
            $query->whereYear('schedule.start_date_time', $year);  // Filter by year
        }

        // Execute the query and get the result
        $stateDetails = $query->get();  // Execute the query

        // Pass the data to the view (as JSON)
        return response()->json(view('reports.state', compact('stateDetails', 'request'))->render());
    }
}
