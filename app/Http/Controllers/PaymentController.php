<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use App\Models\JobActivity;  
use App\Models\Manufacturer;
use App\Models\JobProduct;
use App\Models\JobServices;


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

    public function updatePaymentStatus(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'payment_id' => 'required|',
    ]);

    // Find the payment by ID
    $payment = Payment::findOrFail($validatedData['payment_id']);

    // Update payment status to completed
    $payment->status = 'paid';
    $payment->save();

    // Update job table invoice_status to complete if requested job_id matches
    JobModel::where('id', $payment->job_id)->update(['invoice_status' => 'complete']);

    // Store activity in JobActivity model
    JobActivity::create([
        'job_id' => $payment->job_id,
        'user_id' => auth()->id(),
        'activity' => 'Amount Paid (#' . $payment->job_id . ')',
    ]);

            app('sendNotices')('New Invoice', ' Amount Paid ' . now(), url()->current(), 'Invoice');

    return redirect()->back()->with('success', 'Payment status updated successfully.');
}

    public function createPaymentInvoice(Request $request)
{
    $validatedData = $request->validate([
        'job_id' => 'required',
    ]);

    $job = JobModel::findOrFail($validatedData['job_id']);

    $paymentData = [
        'job_id' => $job->id,
        'customer_id' => $job->customer_id,
        'sub_total' => $job->subtotal,
        'discount' => $job->discount,
        'tax' => $job->tax,
        'total' => $job->gross_total,
    ];

    $paymentId = Payment::max('id') + 1;
    $invoiceNumber = 'INV-DC-' . $paymentId;

    $issueDate = now();
    $dueDate = now()->addDays(7);

    $paymentData = array_merge($paymentData, [
        'invoice_number' => $invoiceNumber,
        'issue_date' => $issueDate,
        'due_date' => $dueDate,
        'status' => 'unpaid',
    ]);

    JobModel::where('id', $validatedData['job_id'])->update(['invoice_status' => 'created']);

    $paymentInvoice = Payment::create($paymentData);

return redirect()->route('invoicedetail', ['id' => $paymentInvoice->id]);
}
    public function index()
    {
        $payment = Payment::with('JobAppliances','user', 'JobModel')->latest()->get();

        $manufacturer = Manufacturer::where('is_active','yes')->get();

        $tech = User::where('role', 'technician')->get();

        return view('payment.index', compact('payment', 'manufacturer','tech'));
    }

    public function invoice_detail(Request $request, $id)
    {
        $site = SiteSettings::first();
        $payment = Payment::with('JobModel')->find($id);
         $jobproduct = JobProduct::where('job_id', $payment->job_id)->get();
        $jobservice = JobServices::where('job_id', $payment->job_id)->get();
        
        if ($payment) {
            $job = JobModel::with('jobserviceinfo', 'jobproductinfo')->where('id', $payment->job_id)->first();

            return view('payment.invoice_detail', compact('payment','jobproduct','jobservice', 'job','site'));
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
