<h5 class="card-title uppercase">Jobs</h5>

@if ($tickets->where('customer_id', $commonUser->id)->isEmpty())
<div class="alert alert-secondary mt-4 col-md-12" role="alert">Jobs not available for
    {{ $commonUser->name ?? '' }}. <strong><a href="{{ route('schedule') }}">Add
            New</a></strong></div>
@else
<div class="table-responsive table-custom2 mt-2">
    <table id="zero_config" class="table table-hover table-striped text-nowrap" data-paging="true" data-paging-size="7">
        <thead>
            <tr>
                <th>Job No</th>
                <th>Job Details</th>
                <th>Customer</th>
                <th>Technician</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets->where('customer_id', $commonUser->id) as $ticket)
            <tr>
                <td>
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="fw-bold link"><span
                            class="mb-1 badge bg-primary">#{{ $ticket->id }}</span></a>
                </td>
                <td>
                    <div class="text-wrap2">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium link">{{
                            $ticket->job_title ?? null }}</a>
                        <span class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
                    </div>
                    <div style="font-size:12px;">
                        @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->apliencename)
                        {{ $ticket->jobdetailsinfo->apliencename->appliance_name }}/
                        @endif
                        @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->manufacturername)
                        {{ $ticket->jobdetailsinfo->manufacturername->manufacturer_name }}/
                        @endif
                        @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->model_number)
                        {{ $ticket->jobdetailsinfo->model_number }}/
                        @endif
                        @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->serial_number)
                        {{ $ticket->jobdetailsinfo->serial_number }}
                        @endif
                    </div>
                </td>
                <td>
                    @if ($ticket->user)
                    <a href="{{ route('users.show', $ticket->user->id) }}" class="link">{{ $ticket->user->name
                        }}</a>
                    @else
                    Unassigned
                    @endif
                </td>
                <td>
                    @if ($ticket->technician)
                    <a href="{{ route('technicians.show', $ticket->technician->id) }}" class="link">{{
                        $ticket->technician->name }}</a>
                    @else
                    Unassigned
                    @endif
                </td>
                <td>
                    @php
                    // Original datetime strings
                    $datetimeString = $ticket->JobAssign->start_date_time ?? null;
                    $datetimeString1 = $ticket->JobAssign->end_date_time ?? null;

                    // Time interval from session
                    $time_interval = Session::get('time_interval');

                    // Calculate the new date and time if original datetime strings are not null
                    $newFormattedDateTime = $datetimeString ?
                    \Carbon\Carbon::parse($datetimeString)->addHours($time_interval) : null;
                    $newFormattedDateTime1 = $datetimeString1 ?
                    \Carbon\Carbon::parse($datetimeString1)->addHours($time_interval) : null;
                    @endphp

                    @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time && $newFormattedDateTime &&
                    $newFormattedDateTime1)
                    <div class="font-medium link">
                        {{ $newFormattedDateTime->format('m-d-Y') }}
                    </div>
                    <div style="font-size: 12px;">
                        {{ $newFormattedDateTime->format('h:i A') }}
                        to
                        {{ $newFormattedDateTime1->format('h:i A') }}
                    </div>
                    @else
                    <div></div>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif