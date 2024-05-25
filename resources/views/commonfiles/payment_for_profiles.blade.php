<h5 class="card-title uppercase">Payments & Invoices</h5>
@if ($payments->isEmpty())
<div class="alert alert-info mt-4" role="alert">
    Payments not available for {{ $commonUser->name ?? '' }}.
    <strong><a href="{{ route('schedule') }}">Add New</a></strong>
</div>
@else
<div class="table-responsive table-custom2 mt-2">
    <table id="zero_config2" class="table table-hover table-striped text-nowrap" data-paging="true"
        data-paging-size="7">
        <thead>
            <tr>
                <th># Invoice No.</th>
                <th>Job Details</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Technician</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                @php
                $jobname = DB::table('jobs')
                ->where('id', $payment->job_id)
                ->first();
                @endphp
                <td>
                    <a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}" class="font-medium link">{{
                        $payment->id ?? 'N/A' }}</a>
                </td>

                <td><a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}" class="font-medium link">{{
                        $jobname->job_title ?? 'N/A' }}</a>
                </td>

                <td>{{ isset($payment->created_at) ?
                    \Carbon\Carbon::parse($payment->created_at)->format('m-d-Y @ g:ia') :
                    null }}
                </td>
                <td>{{ $payment->total ?? null }}</td>
                <td>{{ $payment->status ?? null }} </td>
                <td>
                    @php
                    $job = DB::table('jobs')
                    ->where('id', $payment->job_id)
                    ->first(); // Retrieve job details
                    if ($job) {
                    $technician1 = DB::table('users')
                    ->where('id', $job->technician_id)
                    ->first(); // Retrieve technician details
                    $technician_name = $commonUser
                    ? $commonUser->name
                    : 'Unknown';
                    // Get technician's name or set to 'Unknown' if not found
                    } else {
                    $technician_name = 'Unknown';
                    }
                    @endphp
                    <a href="{{ route('technicians.show', ['id' => $technician1->id]) }}" class="link">{{
                        $technician1->name ?? null }}</a>
                </td>


                <td>@php
                    $customer = DB::table('users')
                    ->where('id', $payment->customer_id)
                    ->first();
                    @endphp
                    <a href="{{ route('users.show', ['id' => $customer->id]) }}" class="link">{{ $customer->name ?? null
                        }}</a>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
<br />