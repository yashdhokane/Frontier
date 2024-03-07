<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use App\Models\Manufacturer;
use App\Models\Payment;
use App\Models\PaymentNotes;
use App\Models\SiteSettings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// use Google\Service\AnalyticsReporting\User;

class PaymentController extends Controller
{
    public function index()
    {
        $payment = Payment::with('user', 'JobModel')->latest()->get();

        $manufacturer = Manufacturer::where('is_active','yes')->get();

        $tech = User::where('role', 'technician')->get();

        return view('payment.index', compact('payment', 'manufacturer','tech'));
    }

    public function invoice_detail(Request $request, $id)
    {
        $site = SiteSettings::first();
        $payment = Payment::with('JobModel')->find($id);
        if ($payment) {
            $job = JobModel::with('jobserviceinfo', 'jobproductinfo')->where('id', $payment->job_id)->first();

            return view('payment.invoice_detail', compact('payment', 'job','site'));
        } else {
            return view('404');
        }
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        $payment->status = 'paid';

        $payment->update();

        return redirect()->back();
    }

    public function comment(Request $request, $id)
    {
        $user = auth()->user()->id;
        $payment = new PaymentNotes();

        $payment->payment_id = $id;
        $payment->user_id = $user;
        $payment->payment_note = $request->payment_note;

        $payment->save();

        return redirect()->back()->with('success', 'Comment added successfully');
    }
}
