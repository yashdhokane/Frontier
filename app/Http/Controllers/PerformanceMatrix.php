<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PerformanceMatrix extends Controller
{
    public function performanncematrix()
    {
        // Fetch technician_id, count of assigned jobs, and count of completed jobs for each technician
        $performances = DB::table('jobs')
            ->select(
                'jobs.technician_id',
                DB::raw('COUNT(jobs.id) as total_assigned'),
                DB::raw('SUM(CASE WHEN jobs.status = "closed" THEN 1 ELSE 0 END) as total_completed'),
                'users.name' // Selecting the technician's name from the users table
            )
            ->join('users', 'jobs.technician_id', '=', 'users.id') // Joining the jobs table with the users table based on technician_id
            ->groupBy('jobs.technician_id', 'users.name')
            ->get();

        // Calculate percentage completion for each technician
        foreach ($performances as $performance) {
            $performance->percentage_completed = ($performance->total_completed / $performance->total_assigned) * 100;
        }

        // Sort technicians based on percentage completion
        $performances = $performances->sortByDesc('percentage_completed');

        // Divide technicians into different performance categories
        $topPerformers = $performances->where('percentage_completed', '>=', 50)->take(5);
        $goodPerformers = $performances->where('percentage_completed', '>=', 0)->where('percentage_completed', '<', 50)->take(5);
        $poorPerformers = $performances->where('percentage_completed', '>=', 0)->where('percentage_completed', '<', 30)->take(5);
        $allPerformers = $performances->where('percentage_completed', '>=', 0)->where('percentage_completed', '<', 100);
      $successratecounts = $performances->where('percentage_completed', '>=', 0)->where('percentage_completed', '<', 100)->count();

        // dd($allPerformers);
        // Pass the variables to the blade view
          // Count jobs with status = rejected
        $rejectedCount = DB::table('jobs')
            ->where('status', 'rejected')
            ->count();

        // Count jobs with status = closed
        $closedCount = DB::table('jobs')
            ->where('status', 'closed')
            ->count();

        // Count jobs with status = pending
        $pendingCount = DB::table('jobs')
            ->where('status', 'pending')
            ->count();

        // Count jobs with status = open
        $openCount = DB::table('jobs')
            ->where('status', 'open')
            ->count();

$startDate = Carbon::now()->subMonth()->startOfMonth();

// Get the end date of the last month
$endDate = Carbon::now()->subMonth()->endOfMonth();

// Get the total count of jobs generated in the last month
$onemonthCount = DB::table('jobs')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->count();

// Get the count of closed jobs for the last month
$onemonthcompleteCount = DB::table('jobs')
    ->where('status', 'closed')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->count();

// Calculate the percentage of closed jobs relative to the total count
if ($onemonthCount > 0) {
    $closedPercentage = ($onemonthcompleteCount / $onemonthCount) * 100;
    $formattedPercentage = number_format($closedPercentage, 2); // Format to two decimal places
} else {
    $formattedPercentage = 0; // Handle division by zero error
}

//earning
// Get the one-month total earning count from the gross_total column
$oneMonthTotalEarningCount = DB::table('jobs')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->sum('gross_total'); // Assuming 'gross_total' is the column storing earnings

// Get the count of closed earnings for the last month
$oneMonthClosedEarningCount = DB::table('jobs')
    ->where('status', 'closed')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->sum('gross_total'); 
// Calculate the percentage of closed earnings relative to the total earnings count
if ($oneMonthTotalEarningCount > 0) {
    $closedEarningPercentage = ($oneMonthClosedEarningCount / $oneMonthTotalEarningCount) * 100;
    $formattedPercentage = number_format($closedEarningPercentage, 2); // Format to two decimal places
} else {
    $formattedPercentage = 0; // Handle division by zero error
}

        return view('performancematrix.performance_matrix', compact('oneMonthClosedEarningCount','formattedPercentage','oneMonthTotalEarningCount','topPerformers','onemonthcompleteCount','formattedPercentage','onemonthCount','successratecounts', 'closedCount', 'pendingCount', 'openCount', 'rejectedCount', 'allPerformers', 'goodPerformers', 'poorPerformers'));
    }





}