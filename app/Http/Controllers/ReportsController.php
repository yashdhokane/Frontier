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

class ReportsController extends Controller
{
    //techncian reorts
    public function technicianreport()
    {
        $technician = User::where('role', 'technician')->get();

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

        // Get the current date
        $currentDate = Carbon::today();
        $currentWeekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $currentWeekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        $currentMonth = Carbon::now()->month;
        $currentMonthschedule = Carbon::now()->format('Y-m');

        $todayGrossTotalSum = JobAssign::whereDate('start_date_time', $currentDate)
            ->join('jobs', 'job_assigned.job_id', '=', 'jobs.id')
            ->sum('jobs.gross_total');

        $monthGrossTotalSum = JobAssign::whereMonth('start_date_time', $currentMonth)
            ->join('jobs', 'job_assigned.job_id', '=', 'jobs.id')
            ->sum('jobs.gross_total');

        $mothlyjobcount = JobAssign::whereMonth('start_date_time', $currentMonth)
            ->join('jobs', 'job_assigned.job_id', '=', 'jobs.id')
            ->count();

        $todayjobcount = JobAssign::whereDate('start_date_time', $currentDate)
            ->join('jobs', 'job_assigned.job_id', '=', 'jobs.id')
            ->count();

        $daillyjobcount = Schedule::whereDate('start_date_time', $currentDate)->count();

        $weeklyjobcount = Schedule::whereDate('start_date_time', [$currentWeekStartDate, $currentWeekEndDate])->count();

        $monthjobcount = Schedule::where('start_date_time', 'LIKE', "$currentMonthschedule%")->count();

        // Get all site tag IDs
        $siteTagIds = SiteTags::pluck('tag_id');

        // Initialize an array to store the count of jobs for each site tag
        $siteTagCounts = [];

        // Loop through each site tag ID
        foreach ($siteTagIds as $tagId) {
            // Get the count of jobs that use the current site tag
            $count = JobModel::where('tag_ids', 'LIKE', "%$tagId%")->count();

            // Store the count in the array with the site tag ID as key
            $siteTagCounts[$tagId] = $count;
        }

        // Total count of jobs using any site tag
        $totalSiteTagUsage = array_sum($siteTagCounts);

        $lowcount = JobModel::where('priority', 'low')->count();
        $mediumcount = JobModel::where('priority', 'medium')->count();
        $highcount = JobModel::where('priority', 'high')->count();

        // Get all site tag IDs
        $lead = SiteLeadSource::pluck('source_id');

        // Get the count of jobs that use any of the site tags
        $sourcelead = User::whereIn('source_id', $lead)->count();

        $customerCount = JobModel::distinct('customer_id')->count('customer_id');

        $zipcode = JobModel::distinct('zipcode')->count('zipcode');

        $city = JobModel::distinct('city')->count('city');

        $state = JobModel::distinct('state')->count('state');

        $applianaces = JobModel::distinct('appliances_id')->count('appliances_id');




        return view('reports.job', compact('todayGrossTotalSum', 'monthGrossTotalSum', 'mothlyjobcount', 'todayjobcount', 'daillyjobcount', 'weeklyjobcount', 'monthjobcount', 'totalSiteTagUsage', 'lowcount', 'mediumcount', 'highcount','sourcelead',
    'customerCount','zipcode','city','state','applianaces'));
    }
}
