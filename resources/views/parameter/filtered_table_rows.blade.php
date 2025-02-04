@php
    $time_interval = Session::get('time_interval', 0);
@endphp
@foreach ($tickets as $ticket)
<tr>
    <td>
        <a href="{{ route('tickets.show', $ticket->id) }}" class="fw-bold link">
            <span class="mb-1 badge bg-primary">{{ $ticket->id }}</span>
        </a>
    </td>
    <td class="job-details-column">
        <div class="text-wrap2 d-flex">
            <div class="text-truncate w-25">
                <a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium link">
                    {{ $ticket->job_title ?? null }}
                </a>
            </div>
            <span class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
        </div>
        <div style="font-size:12px;">
            @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                {{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
            @endif
            @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                {{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
            @endif
            @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->model_number)
                {{ $ticket->JobAppliances->Appliances->model_number ?? null }}/
            @endif
            @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->serial_number)
                {{ $ticket->JobAppliances->Appliances->serial_number ?? null }}
            @endif
        </div>
    </td>
    <td>
        {{ $ticket->user->name ?? 'Unassigned' }}
    </td>
    <td>
        {{ $ticket->technician->name ?? 'Unassigned' }}
    </td>
    <td>
        @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
            <div class="font-medium link">
                {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
            </div>
        @else
            <div></div>
        @endif
        <div style="font-size:12px;">
            {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
            to
            {{ $modifyDateTime($ticket->jobassignname->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
        </div>
    </td>
</tr>
@endforeach
