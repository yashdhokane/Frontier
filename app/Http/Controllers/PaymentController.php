<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use App\Models\JobActivity;
use App\Models\Manufacturer;
use App\Models\JobProduct;
use App\Models\JobServices;
use App\Models\JobTechEvents;




use App\Models\Payment;
use App\Models\PaymentNotes;
use App\Models\SiteSettings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

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
        $timezone_name = Session::get('timezone_name');

        $payment = Payment::findOrFail($validatedData['payment_id']);

        // $payment->created_at = Carbon::now($timezoneName);
        $payment->updated_at = Carbon::now($timezone_name);
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

        $event = JobTechEvents::where('job_id', $payment->job_id)->first();
        if ($event) {
            $event->job_payment = Carbon::now($timezone_name);
            $event->save();
        }

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
        $timezone_name = Session::get('timezone_name');

        $event = JobTechEvents::where('job_id', $job->id)->first();
        if ($event) {
            $event->job_invoice = Carbon::now($timezone_name);
            $event->save();
        }

        return redirect()->route('invoicedetail', ['id' => $paymentInvoice->id]);
    }
    public function index()
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 33;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $payments = Payment::with('JobAppliances', 'user', 'JobModel')->latest('id')->get();

        $manufacturer = Manufacturer::where('is_active', 'yes')->get();

        $tech = User::where('role', 'technician')->where('status', 'active')->get();

        return view('payment.index', compact('payments', 'manufacturer', 'tech'));
    }

    public function invoice_detail(Request $request, $id)
    {
        $site = SiteSettings::first();
        $payment = Payment::with('JobModel')->find($id);

        if (!$payment) {
            return view('404');
        }

        $jobproduct = JobProduct::where('job_id', $payment->job_id)->get();
        $jobservice = JobServices::where('job_id', $payment->job_id)->get();

        $job = JobModel::with('jobserviceinfo', 'jobproductinfo')->where('id', $payment->job_id)->first();

        return view('payment.invoice_detail', compact('payment', 'jobproduct', 'jobservice', 'job', 'site'));
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

        return redirect()->back()->with('success', 'The comment has been added successfully.');
    }
}
