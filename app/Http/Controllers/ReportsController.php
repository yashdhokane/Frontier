<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReportsController extends Controller
{
    //techncian reorts
   public function technicianreport(){
    $technician = User::where('role', 'technician')->get();
    return view('reports.technician', compact('technician'));
}

public function employeereport(){
    $employees = User::whereNotIn('role', ['technician', 'customer'])->get();
    return view('reports.employees', compact('employees'));
}
public function jobreport(){
  
    return view('reports.job');
}
}
