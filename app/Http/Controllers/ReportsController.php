<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\JobActivity;
use App\Models\JobAssign;
use App\Models\JobModel;
use Illuminate\Http\Request;
use App\Models\User;

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

        return view('reports.technician', compact('technician','job','jobcount','grossTotalByTechnician','jobCountsByTechnician','totalHours','jobHours','totaldrivingHours','drivingHours'));
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
    
    
        return view('reports.employees', compact('job','employees', 'jobCountsByEmployee','jobCountsUpdatedBy','jobCountsClosedBy','grossTotalByEmployee','alltotalGross','activity','totalActivity','chats','totalChats'));
    }
    
    public function jobreport()
    {

        return view('reports.job');
    }
}
