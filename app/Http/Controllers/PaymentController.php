<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// use Google\Service\AnalyticsReporting\User;

class PaymentController extends Controller
{
    public function index()
    {
        $payment = Payment::with('user','JobModel')->latest()->get();

       return view('payment.index',compact('payment'));
    }

    public function invoice_detail(Request $request , $id)
    {
        $payment = Payment::find($id);
        
        $job = JobModel::with('jobserviceinfo','jobproductinfo')->where('id',$payment->job_id)->first();

       return view('payment.invoice_detail',compact('payment','job'));
    }


}
